<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

//incluimos Http
use Illuminate\Support\Facades\Http;

class LoginController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
{
    // Realizamos la petición GET a la API de Node.js
    $response = Http::get('http://localhost:3000/login');

    // Verificamos si la petición fue exitosa (código 200)
    if ($response->successful()) {
        // Obtenemos los datos JSON como un array asociativo
        $login = $response->json();

        // Enviamos los datos a la vista 'usuarios'
        return view('login', ['ResulUsuarios' => $login]);
    } else {
        // En caso de error, puedes manejarlo aquí, por ejemplo:
        return view('login', ['ResulLogin' => []])->with('error', 'No se pudieron obtener los datos de la API.');
    }
}

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}