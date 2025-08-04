<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class UsuariosController extends Controller
{
    public function index()
    {
        $response = Http::get('http://localhost:3000/usuarios');

        if ($response->successful()) {
            $usuarios = $response->json();
            return view('usuarios', ['ResulUsuarios' => $usuarios]);
        } else {
            return view('usuarios', ['ResulUsuarios' => []])
                ->with('error', 'No se pudieron obtener los datos de la API.');
        }
    }

    public function store(Request $request)
{

    // Obtener lista de personas para evitar duplicados
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
        'cod_persona'        => $codPersona,
        'DNI'                => $request->dni,
        'nombre'             => $request->nombre,
        'apellido'           => $request->apellido,
        'nacionalidad'       => $request->nacionalidad,
        'genero'             => $request->genero,
        'fecha_nacimiento'   => $request->fecha_nacimiento,
        'telefono'           => $request->telefono,
        'direccion'          => $request->direccion
    ];

    $personaResponse = Http::post('http://localhost:3000/personas', $datosPersona);

    if (!$personaResponse->successful()) {
        return redirect()->route('usuarios.index')->with('error', 'Error al registrar persona: ' . $personaResponse->body());
    }

    // Crear usuario con el mismo cod_persona como cod_usuario
    $datosUsuario = [
        'cod_usuario'     => $codPersona,
        'cod_permiso'     => $request->cod_permiso,
        'nombre_usuario'  => $request->nombre_usuario,
        'contrasena'      => $request->contrasena,
        'estado_usuario'  => $request->estado_usuario,
        'primer_acceso'   => 1,
        'cod_bitacora'    => 0
    ];

    $usuarioResponse = Http::post('http://localhost:3000/usuarios', $datosUsuario);

    if (!$usuarioResponse->successful()) {
        return redirect()->route('usuarios.index')->with('error', 'ERROR AL REGISTRAR USUARIO: ' . $usuarioResponse->body());
    }

    return redirect()->route('usuarios.index')->with('success', 'USUARIO DE MANERA EXITOSA.');
}


        public function update(Request $request, string $id = null)
        {
            $codUsuario = $request->cod_usuario;

            // Mapeamos los campos a actualizar
            $campos = [
                'cod_permiso'     => $request->cod_permiso,
                'nombre_usuario'  => $request->nombre_usuario,
                'contrasena'      => $request->contrasena,
                'estado_usuario'  => $request->estado_usuario,
                'primer_acceso'   => $request->primer_acceso,
                'cod_bitacora'    => $request->cod_bitacora
            ];

            $errores = [];

            // Enviar una solicitud por cada campo
            foreach ($campos as $campo => $valor) {
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

            if (count($errores) > 0) {
                return redirect()->route('usuarios.index')->with('error', 'Error al actualizar los campos: ' . implode(', ', $errores));
            }

            return redirect()->route('usuarios.index')->with('success', 'USUARIO ACTUALIZADO DE MANERA EXITOSA.');
        }

    public function create() {}
    public function show(string $id) {}
    public function edit(string $id) {}
    public function destroy(string $id) {}
}
