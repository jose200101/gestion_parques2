<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

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

    // Stubs (si los necesitas)
    public function create(){}
    public function show(string $id){}
    public function edit(string $id){}
}
