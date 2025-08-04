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
        // 1. Obtener todos los usuarios existentes
        $response = Http::get('http://localhost:3000/usuarios');
        if (!$response->successful()) {
            return redirect()->route('usuarios.index')->with('error', 'Error al validar usuarios existentes.');
        }

        $usuarios = $response->json();

        // 2. Verificar si el nombre de usuario ya existe
        foreach ($usuarios as $usuario) {
            if (
                strtolower($usuario['nombre_usuario']) === strtolower($request->nombre_usuario)
            ) {
                return redirect()->route('usuarios.index')->with('error', 'El nombre de usuario ya existe.');
            }
        }

        // 3. Calcular automáticamente el próximo cod_usuario
        $maxCodUsuario = 0;
        foreach ($usuarios as $usuario) {
            if (is_numeric($usuario['cod_usuario']) && $usuario['cod_usuario'] > $maxCodUsuario) {
                $maxCodUsuario = $usuario['cod_usuario'];
            }
        }
        $nuevoCodUsuario = $maxCodUsuario + 1;

        // 4. Preparar los datos para enviar al backend
        $data = [
            'cod_usuario'     => $nuevoCodUsuario,
            'cod_permiso'     => $request->cod_permiso,
            'nombre_usuario'  => $request->nombre_usuario,
            'contrasena'      => $request->contrasena,
            'estado_usuario'  => $request->estado_usuario,
            'primer_acceso'   => 1,
            'cod_bitacora'    => 0
        ];

        // 5. Enviar a la API
        $insertResponse = Http::post('http://localhost:3000/usuarios', $data);

        if ($insertResponse->successful()) {
            return redirect()->route('usuarios.index')->with('success', 'REGISTRO CREADO DE MANERA EXITOSA.');
        } else {
            return redirect()->route('usuarios.index')->with('error', 'Error al insertar usuario.');
        }
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
