<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ParquesController extends Controller
{
    /**
     * Muestra una lista de todos los parques.
     */
    public function index()
    {
        // Se asume que esta parte ya funciona correctamente para obtener los datos
        // $response = Http::get('http://localhost:3000/api/parques');
        // $parques = $response->json();
        // return view('parques_index', compact('parques'));
        // Por ahora, para que puedas ver el mensaje de éxito, simulamos los datos.
        $parques = [
            ['id' => 1, 'nombre_parque' => 'Parque del Sol', 'ubicacion_parque' => 'Sector 1', 'fecha_inauguracion' => '2020-01-15', 'estado' => 1],
            ['id' => 2, 'nombre_parque' => 'Parque de la Luna', 'ubicacion_parque' => 'Sector 2', 'fecha_inauguracion' => '2018-05-20', 'estado' => 0],
        ];

        return view('parques_index', compact('parques'));
    }

    /**
     * Muestra el formulario para crear un nuevo parque.
     */
    public function create()
    {
        return view('parques_create');
    }

    /**
     * Almacena un nuevo parque en la base de datos a través de la API.
     */
    public function store(Request $request)
    {
        // Validar los datos del formulario
        $validatedData = $request->validate([
            'nombre_parque' => 'required|string|max:255',
            'ubicacion_parque' => 'required|string|max:255',
            'fecha_inauguracion' => 'required|date',
            'estado' => 'required|integer|in:0,1',
        ]);

        // Realizar la solicitud POST a la API de Node.js
        $response = Http::post('http://localhost:3000/api/parques', [
            'nombre_parque' => $validatedData['nombre_parque'],
            'ubicacion_parque' => $validatedData['ubicacion_parque'],
            'fecha_inauguracion' => $validatedData['fecha_inauguracion'],
            'estado' => $validatedData['estado'],
        ]);

        // Revisa si la solicitud fue exitosa
        if ($response->successful()) {
            // Redirigir al índice de parques con un mensaje de éxito
            return redirect()->route('parques.index')->with('success', 'Parque creado exitosamente.');
        } else {
            // Si hay un error, redirigir de vuelta con un mensaje de error
            return redirect()->back()->with('error', 'Error al crear el parque. Inténtalo de nuevo.');
        }
    }
}

