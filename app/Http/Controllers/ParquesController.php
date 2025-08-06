<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
// Ana R. Cabrera - Incluimos Http para hacer peticiones a la API
use Illuminate\Support\Facades\Http;

class ParquesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Ana R. Cabrera - Realizamos la petición GET a la API para obtener el listado de parques
        $response = Http::get('http://localhost:3000/parques');

        // Ana R. Cabrera - Verificamos si la petición fue exitosa (código 200)
        if ($response->successful()) {
            // Ana R. Cabrera - Obtenemos los datos JSON como un array asociativo
            $parques = $response->json();

            // Ana R. Cabrera - Enviamos los datos a la vista 'parques_index'
            // Se ha modificado para que coincida con el nombre del archivo 'parques_index.blade.php'
            return view('parques_index', ['ResulParques' => $parques]);
        } else {
            // Ana R. Cabrera - En caso de error, manejamos la respuesta y enviamos una lista vacía
            // Usamos el mismo nombre de vista y pasamos el error para que se pueda mostrar.
            return view('parques_index', ['ResulParques' => []])->with('error', 'No se pudieron obtener los datos de la API.');
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Ana R. Cabrera - Muestra el formulario para crear un nuevo parque
        // Se ha modificado para que coincida con el nombre del archivo 'parques_create.blade.php'
        return view('parques_create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Ana R. Cabrera - Lógica para guardar un nuevo parque usando la API
        try {
            // Creamos un array con los datos del formulario, asegurando que los nombres de las
            // claves coincidan exactamente con las columnas de la base de datos.
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
                return back()->with('error', 'No se pudo crear el parque.');
            }
        } catch (\Exception $e) {
            return back()->with('error', 'Error al conectar con la API: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $cod_parque)
    {
        // Ana R. Cabrera - Lógica para mostrar los detalles de un parque específico
        $response = Http::get("http://localhost:3000/parques/{$cod_parque}");

        if ($response->successful()) {
            $parque = $response->json();
            // Se ha modificado para que coincida con el nombre del archivo 'parques_show.blade.php'
            return view('parques_show', ['parque' => $parque]);
        } else {
            return back()->with('error', 'No se pudo obtener el parque.');
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $cod_parque)
    {
        // Ana R. Cabrera - Lógica para mostrar el formulario de edición de un parque
        $response = Http::get("http://localhost:3000/parques/{$cod_parque}");

        if ($response->successful()) {
            // ANA R. CABRERA - La respuesta de la API para un solo recurso
            // a menudo viene dentro de un array. Aseguramos que solo tomamos el primer elemento.
            $parque = $response->json()[0];

            // Se ha modificado para que coincida con el nombre del archivo 'parques_edit.blade.php'
            return view('parques_edit', ['parque' => $parque]);
        } else {
            return back()->with('error', 'No se pudo obtener el parque para editar.');
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $cod_parque)
    {
        // Ana R. Cabrera - Lógica para actualizar un parque usando la API
        $response = Http::put("http://localhost:3000/parques/{$cod_parque}", $request->all());

        if ($response->successful()) {
            return redirect()->route('parques.index')->with('success', 'Parque actualizado con éxito.');
        } else {
            return back()->with('error', 'No se pudo actualizar el parque.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    // Ana R. Cabrera - Este método se ha dejado vacío porque no tenemos un proceso de eliminación
    public function destroy(string $id)
    {
        // No se implementa la lógica de eliminación
    }
}

