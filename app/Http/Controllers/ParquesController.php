<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log; // Ana R. Cabrera - Importamos la clase Log para registrar eventos

class ParquesController extends Controller
{
    /**
     * Muestra una lista de los recursos (parques).
     */
    public function index()
    {
        // Ana R. Cabrera - Realizamos la petición GET a la API para obtener el listado de parques
        try {
            $response = Http::get('http://localhost:3000/parques');

            // Ana R. Cabrera - Verificamos si la petición fue exitosa (código 200)
            if ($response->successful()) {
                // Obtenemos los datos JSON como un array asociativo
                $parques = $response->json();
                // Enviamos los datos a la vista 'parques.parques_index'
                return view('parques.parques_index', ['ResalParques' => $parques]);
            } else {
                // En caso de error, registramos el problema y enviamos una lista vacía
                Log::error('API Error al obtener parques: ' . $response->status() . ' - ' . $response->body());
                return view('parques.parques_index', ['ResalParques' => []])->with('error', 'No se pudieron obtener los datos de la API.');
            }
        } catch (\Exception $e) {
            // Si hay un error de conexión, lo registramos
            Log::error('Error de conexión con la API: ' . $e->getMessage());
            return view('parques.parques_index', ['ResalParques' => []])->with('error', 'Error al conectar con la API.');
        }
    }

    /**
     * Muestra el formulario para crear un nuevo recurso.
     */
    public function create()
    {
        // Ana R. Cabrera - Muestra el formulario para crear un nuevo parque
        return view('parques.parques_create');
    }

    /**
     * Almacena un recurso recién creado en la base de datos.
     */
    public function store(Request $request)
    {
        // Ana R. Cabrera - Lógica para guardar un nuevo parque usando la API
        try {
            $data = [
                'nombre_parque' => $request->input('nombre_parque'),
                'ubicacion_parque' => $request->input('ubicacion_parque'),
                'fecha_inauguracion' => $request->input('fecha_inauguracion'),
                'estado' => $request->input('estado')
            ];

            $response = Http::post('http://localhost:3000/parques', $data);

            if ($response->successful()) {
                return redirect()->route('parques.index')->with('success', 'Parque creado con éxito.');
            } else {
                Log::error('API Error al crear parque: ' . $response->status() . ' - ' . $response->body());
                return back()->with('error', 'No se pudo crear el parque.');
            }
        } catch (\Exception $e) {
            Log::error('Error de conexión al crear parque: ' . $e->getMessage());
            return back()->with('error', 'Error al conectar con la API: ' . $e->getMessage());
        }
    }

    /**
     * Muestra el recurso especificado.
     */
    public function show(string $cod_parque)
    {
        // Ana R. Cabrera - Lógica para mostrar los detalles de un parque específico
        try {
            $response = Http::get("http://localhost:3000/parques/{$cod_parque}");

            if ($response->successful()) {
                $parque = $response->json();
                return view('parques.parques_show', ['parque' => $parque]);
            } else {
                Log::error('API Error al mostrar parque: ' . $response->status() . ' - ' . $response->body());
                return back()->with('error', 'No se pudo obtener el parque.');
            }
        } catch (\Exception $e) {
            Log::error('Error de conexión al mostrar parque: ' . $e->getMessage());
            return back()->with('error', 'Error al conectar con la API.');
        }
    }

    /**
     * Muestra el formulario para editar un recurso.
     */
    public function edit(string $cod_parque)
    {
        // Ana R. Cabrera - Lógica para mostrar el formulario de edición de un parque
        try {
            $response = Http::get("http://localhost:3000/parques/{$cod_parque}");

            if ($response->successful()) {
                // ANA R. CABRERA - La respuesta de la API para un solo recurso
                // a menudo viene dentro de un array. Aseguramos que solo tomamos el primer elemento.
                $parque = $response->json()[0];
                return view('parques.parques_edit', ['parque' => $parque]);
            } else {
                Log::error('API Error al editar parque: ' . $response->status() . ' - ' . $response->body());
                return back()->with('error', 'No se pudo obtener el parque para editar.');
            }
        } catch (\Exception $e) {
            Log::error('Error de conexión al editar parque: ' . $e->getMessage());
            return back()->with('error', 'Error al conectar con la API.');
        }
    }

    /**
     * Actualiza el recurso especificado en la base de datos.
     */
    public function update(Request $request, string $cod_parque)
    {
        // Ana R. Cabrera - Lógica para actualizar un parque usando la API
        try {
            $response = Http::put("http://localhost:3000/parques/{$cod_parque}", $request->all());

            if ($response->successful()) {
                return redirect()->route('parques.index')->with('success', 'Parque actualizado con éxito.');
            } else {
                Log::error('API Error al actualizar parque: ' . $response->status() . ' - ' . $response->body());
                return back()->with('error', 'No se pudo actualizar el parque.');
            }
        } catch (\Exception $e) {
            Log::error('Error de conexión al actualizar parque: ' . $e->getMessage());
            return back()->with('error', 'Error al conectar con la API.');
        }
    }

    /**
     * Elimina el recurso especificado de la base de datos.
     */
    public function destroy(string $cod_parque)
    {
        // Ana R. Cabrera - Lógica para eliminar un parque usando la API.
        try {
            // Realizamos la petición DELETE a la API para eliminar el parque por su código
            $response = Http::delete("http://localhost:3000/parques/{$cod_parque}");
            
            // Ana R. Cabrera - Registramos la respuesta completa de la API para depuración
            Log::info("Respuesta de la API para DELETE parque {$cod_parque}: " . $response->body());

            // Verificamos si la petición fue exitosa
            if ($response->successful()) {
                return redirect()->route('parques.index')->with('success', 'Parque eliminado correctamente.');
            } else {
                // Si hubo un error en la API, volvemos a la página anterior con un mensaje de error
                Log::error('API Error al eliminar parque: ' . $response->status() . ' - ' . $response->body());
                return back()->with('error', 'No se pudo eliminar el parque desde la API.');
            }
        } catch (\Exception $e) {
            // Capturamos cualquier error de conexión y lo mostramos
            Log::error('Error de conexión al eliminar parque: ' . $e->getMessage());
            return back()->with('error', 'Error al conectar con la API: ' . $e->getMessage());
        }
    }
}

