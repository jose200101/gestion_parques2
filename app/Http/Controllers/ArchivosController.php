<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http; 
use Illuminate\Support\Facades\Log;

class ArchivosController extends Controller
{
    /**
     * Muestra el formulario para subir archivos.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $parques = collect([]); // Ana R. Cabrera: Inicializamos una colección vacía para evitar errores.
        
        try {
            $response = Http::get('http://localhost:3000/parques');

            if ($response->successful()) {
                $parques = collect($response->json()); // Ana R. Cabrera: Convertimos los datos JSON en una colección.
            } else {
                Log::error('API Error al obtener parques para ArchivosController: ' . $response->status() . ' - ' . $response->body());
                return view('archivos.create', compact('parques'))->with('error', 'No se pudieron obtener los datos de la API.');
            }
        } catch (\Exception $e) {
            Log::error('Error de conexión con la API en ArchivosController: ' . $e->getMessage());
            return view('archivos.create', compact('parques'))->with('error', 'Error al conectar con la API.');
        }

        // Ana R. Cabrera: Devolvemos la vista con la colección de parques (puede ser vacía)
        return view('archivos.create', compact('parques'));
    }

    /**
     * Almacena el archivo subido en el sistema de archivos del servidor.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'documento' => 'required|file|mimes:pdf,doc,docx|max:2048',
            'cod_parque' => 'required|integer' 
        ]);

        $parqueId = $request->input('cod_parque');
        
        $file = $request->file('documento');

        $path = $file->storeAs("uploads/{$parqueId}", $file->getClientOriginalName(), 'public');

        return back()->with('success', 'Archivo subido correctamente.');
    }
}


