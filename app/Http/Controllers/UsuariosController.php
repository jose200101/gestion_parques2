<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

class UsuariosController extends Controller
{
    public function index(Request $request)
    {
        $response = Http::get('http://localhost:3000/usuarios');

        if ($response->successful()) {
            $usuarios = $response->json();

            // Filtro por nombre de usuario
            $buscar = trim($request->query('buscar', ''));
            if ($buscar !== '') {
                $usuarios = array_values(array_filter($usuarios, function ($u) use ($buscar) {
                    $nombre = $u['nombre_usuario'] ?? '';
                    return stripos($nombre, $buscar) !== false;
                }));
            }

            return view('usuarios', [
                'ResulUsuarios' => $usuarios,
                'buscar' => $buscar,
            ]);
        }

        return view('usuarios', [
            'ResulUsuarios' => [],
            'buscar' => $request->query('buscar', '')
        ])->with('error', 'No se pudieron obtener los datos de la API.');
    }

    public function store(Request $request)
    {
        // Obtener personas para evitar duplicados de cod_persona
        $personasResponse = Http::get('http://localhost:3000/personas');
        $personas = $personasResponse->ok() ? $personasResponse->json() : [];

        // Generar cod_persona único entre 1000 y 2000
        $codPersona = null;
        do {
            $temp = rand(1000, 2000);
            $existe = collect($personas)->contains('cod_persona', $temp);
            if (!$existe) {
                $codPersona = $temp;
            }
        } while (is_null($codPersona));

        // Crear persona
        $datosPersona = [
            'cod_persona'      => $codPersona,
            'DNI'              => $request->dni,
            'nombre'           => $request->nombre,
            'apellido'         => $request->apellido,
            'nacionalidad'     => $request->nacionalidad,
            'genero'           => $request->genero,
            'fecha_nacimiento' => $request->fecha_nacimiento,
            'telefono'         => $request->telefono,
            'direccion'        => $request->direccion
        ];

        $personaResponse = Http::post('http://localhost:3000/personas', $datosPersona);
        if (!$personaResponse->successful()) {
            return redirect()->route('usuarios.index')
                ->with('error', 'Error al registrar persona: ' . $personaResponse->body());
        }

        // Crear usuario (cod_usuario = cod_persona generado)
        $datosUsuario = [
            'cod_usuario'    => $codPersona,
            'cod_permiso'    => $request->cod_permiso,
            'nombre_usuario' => $request->nombre_usuario,
            'contrasena'     => $request->contrasena,
            'estado_usuario' => $request->estado_usuario,
            'primer_acceso'  => 1,
            'cod_bitacora'   => 0
        ];

        $usuarioResponse = Http::post('http://localhost:3000/usuarios', $datosUsuario);
        if (!$usuarioResponse->successful()) {
            return redirect()->route('usuarios.index')
                ->with('error', 'ERROR AL REGISTRAR USUARIO: ' . $usuarioResponse->body());
        }

        return redirect()->route('usuarios.index')
            ->with('success', 'USUARIO CREADO DE MANERA EXITOSA.');
    }

    public function update(Request $request, string $id = null)
    {
        $codUsuario = $request->cod_usuario;

        // Mapear campos; ignorar los que vengan nulos
        $campos = [
            'cod_permiso'     => $request->cod_permiso,
            'nombre_usuario'  => $request->nombre_usuario,
            'contrasena'      => $request->contrasena,
            'estado_usuario'  => $request->estado_usuario,
            'primer_acceso'   => $request->primer_acceso,
        ];

        // Agregar cod_bitacora solo si es numérico
        if (is_numeric($request->cod_bitacora)) {
            $campos['cod_bitacora'] = $request->cod_bitacora;
        }

        $errores = [];

        foreach ($campos as $campo => $valor) {
            if ($valor === null || $valor === '') continue;

            $data = [
                'dato_actualizar' => $campo,
                'nuevo_dato'      => $valor,
                'condicion'       => 'cod_usuario',
                'v_condicion'     => $codUsuario
            ];

            $response = Http::put('http://localhost:3000/usuarios', $data);
            if (!$response->successful()) {
                $errores[] = $campo;
            }
        }

        if (!empty($errores)) {
            return redirect()->route('usuarios.index')
                ->with('error', 'Error al actualizar los campos: ' . implode(', ', $errores));
        }

        return redirect()->route('usuarios.index')
            ->with('success', 'USUARIO ACTUALIZADO DE MANERA EXITOSA.');
    }

    public function destroy(Request $request, $id)
    {
        // 1) Intentar DELETE real
        $tryDelete = \Illuminate\Support\Facades\Http::delete("http://localhost:3000/usuarios/{$id}");
        if ($tryDelete->successful()) {
            if ($request->ajax()) {
                return response()->json(['status' => 'ok', 'message' => 'Usuario eliminado correctamente.']);
            }
            return redirect()->route('usuarios.index')->with('success', 'Usuario eliminado correctamente.');
        }

        // 2) Fallback: Soft delete (estado inactivo)
        $softDelete = \Illuminate\Support\Facades\Http::put('http://localhost:3000/usuarios', [
            'dato_actualizar' => 'estado_usuario',
            'nuevo_dato'      => 'inactivo',
            'condicion'       => 'cod_usuario',
            'v_condicion'     => $id
        ]);

        if ($softDelete->successful()) {
            if ($request->ajax()) {
                return response()->json(['status' => 'ok', 'message' => 'Usuario desactivado.']);
            }
            return redirect()->route('usuarios.index')->with('success', 'Usuario desactivado.');
        }

        $msg = 'No se pudo eliminar/desactivar: ' . $tryDelete->body();
        if ($request->ajax()) {
            return response()->json(['status' => 'error', 'message' => $msg], 500);
        }
        return redirect()->route('usuarios.index')->with('error', $msg);
    }

    public function dashboard()
    {
        $response = \Illuminate\Support\Facades\Http::get('http://localhost:3000/usuarios');

        if (!$response->successful()) {
            return view('usuarios_dashboard', [
                'totales' => ['total' => 0, 'admin' => 0, 'empleado' => 0, 'visitante' => 0],
                'porRol'  => [],
            ])->with('error', 'No se pudieron obtener los datos de la API.');
        }

        $usuarios = $response->json() ?? [];

        // Mostrar solo activos (mismo criterio que en la tabla principal)
        $usuarios = array_values(array_filter($usuarios, function ($u) {
            $estado = strtolower($u['estado_usuario'] ?? '');
            return in_array($estado, ['activo', 'active', '1']);
        }));

        // Mapeo de permisos
        $nombreRol = function ($cod) {
            return match ((int)$cod) {
                1 => 'Administrador',
                2 => 'Empleado',
                3 => 'Visitante',
                default => 'Desconocido',
            };
        };

        // Totales por rol
        $totales = [
            'total'     => count($usuarios),
            'admin'     => count(array_filter($usuarios, fn($u) => (int)($u['cod_permiso'] ?? 0) === 1)),
            'empleado'  => count(array_filter($usuarios, fn($u) => (int)($u['cod_permiso'] ?? 0) === 2)),
            'visitante' => count(array_filter($usuarios, fn($u) => (int)($u['cod_permiso'] ?? 0) === 3)),
        ];

        // Agrupar usuarios por rol (1,2,3) ya con etiqueta
        $porRol = [
            'Administrador' => array_values(array_filter($usuarios, fn($u) => (int)($u['cod_permiso'] ?? 0) === 1)),
            'Empleado'      => array_values(array_filter($usuarios, fn($u) => (int)($u['cod_permiso'] ?? 0) === 2)),
            'Visitante'     => array_values(array_filter($usuarios, fn($u) => (int)($u['cod_permiso'] ?? 0) === 3)),
        ];

        return view('usuarios_dashboard', compact('totales', 'porRol'));
    }

    // ========== NUEVAS FUNCIONES PARA PERFIL Y CAMBIO DE CONTRASEÑA ==========

    /**
     * Mostrar el perfil del usuario actual
     */
    public function perfil()
    {
        // Obtener el nombre de usuario de la sesión
        $nombreUsuarioSesion = Session::get('usuario');
        $usuarioCompleto = Session::get('usuario_completo');
        
        if (!$nombreUsuarioSesion && $usuarioCompleto && is_array($usuarioCompleto)) {
            $nombreUsuarioSesion = $usuarioCompleto['nombre_usuario'] ?? null;
        }
        
        if (!$nombreUsuarioSesion) {
            return view('perfil', [
                'user' => null,
                'permisoNombre' => null
            ])->with('error', 'No hay usuario en sesión');
        }

        // Realizar petición GET para obtener todos los usuarios
        $response = Http::get('http://localhost:3000/usuarios');

        if (!$response->successful()) {
            return view('perfil', [
                'user' => null,
                'permisoNombre' => null
            ])->with('error', 'Error al conectar con la API');
        }

        $usuarios = $response->json();
        
        // Buscar el usuario actual por nombre_usuario
        $usuarioActual = null;
        foreach ($usuarios as $usuario) {
            if ($usuario['nombre_usuario'] === $nombreUsuarioSesion) {
                $usuarioActual = $usuario;
                break;
            }
        }

        if (!$usuarioActual) {
            return view('perfil', [
                'user' => null,
                'permisoNombre' => null
            ])->with('error', 'Usuario no encontrado en la base de datos');
        }

        // Obtener información del permiso
        $permisoNombre = $this->obtenerNombrePermiso($usuarioActual['cod_permiso']);

        return view('perfil', [
            'user' => $usuarioActual,
            'permisoNombre' => $permisoNombre
        ]);
    }

    /**
     * Mostrar formulario para cambio de contraseña
     */
    public function mostrarCambioPassword()
    {
        $nombreUsuarioSesion = Session::get('usuario');
        $usuarioCompleto = Session::get('usuario_completo');
        
        if (!$nombreUsuarioSesion && $usuarioCompleto && is_array($usuarioCompleto)) {
            $nombreUsuarioSesion = $usuarioCompleto['nombre_usuario'] ?? null;
        }
        
        if (!$nombreUsuarioSesion) {
            return redirect()->route('login')->with('error', 'Debes iniciar sesión');
        }

        $usuarioString = is_array($nombreUsuarioSesion) ? 
            ($nombreUsuarioSesion['nombre_usuario'] ?? 'Usuario') : 
            $nombreUsuarioSesion;

        return view('cambio-password', ['usuario' => $usuarioString]);
    }

    /**
     * Procesar cambio de contraseña
     */
    public function cambiarPassword(Request $request)
    {
        $request->validate([
            'password_actual' => 'required',
            'password_nuevo' => 'required|min:6',
            'password_confirmacion' => 'required|same:password_nuevo'
        ]);

        $nombreUsuarioSesion = Session::get('usuario');
        $usuarioCompleto = Session::get('usuario_completo');
        
        if (!$nombreUsuarioSesion && $usuarioCompleto && is_array($usuarioCompleto)) {
            $nombreUsuarioSesion = $usuarioCompleto['nombre_usuario'] ?? null;
        }
        
        $usuarioString = is_array($nombreUsuarioSesion) ? 
            ($nombreUsuarioSesion['nombre_usuario'] ?? null) : 
            $nombreUsuarioSesion;
        
        if (!$usuarioString) {
            return redirect()->route('login')->with('error', 'Debes iniciar sesión');
        }

        // Obtener información del usuario actual
        $response = Http::get('http://localhost:3000/usuarios');
        
        if (!$response->successful()) {
            return back()->with('error', 'Error al conectar con la API');
        }

        $usuarios = $response->json();
        $usuarioActual = null;
        
        foreach ($usuarios as $usuario) {
            if ($usuario['nombre_usuario'] === $usuarioString) {
                $usuarioActual = $usuario;
                break;
            }
        }

        if (!$usuarioActual) {
            return back()->with('error', 'Usuario no encontrado');
        }

        // Verificar contraseña actual
        if ($usuarioActual['contrasena'] !== $request->password_actual) {
            return back()->with('error', 'La contraseña actual es incorrecta');
        }

        // Actualizar contraseña usando tu API PUT
        $updateResponse = Http::put('http://localhost:3000/usuarios', [
            'dato_actualizar' => 'contrasena',
            'nuevo_dato' => $request->password_nuevo,
            'condicion' => 'nombre_usuario',
            'v_condicion' => $usuarioString
        ]);

        if ($updateResponse->successful()) {
            return back()->with('success', 'Contraseña actualizada correctamente');
        } else {
            return back()->with('error', 'Error al actualizar la contraseña');
        }
    }

    /**
     * Obtener el nombre del permiso basado en el código
     */
    private function obtenerNombrePermiso($codPermiso)
    {
        $permisos = [
            1 => 'Administrador',
            2 => 'Empleado',
            3 => 'Visitante',
        ];

        return $permisos[$codPermiso] ?? 'Permiso desconocido';
    }

    // Stubs (si los necesitas)
    public function create(){}
    public function show(string $id){}
    public function edit(string $id){}
}