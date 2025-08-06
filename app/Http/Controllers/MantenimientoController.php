<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class MantenimientoController extends Controller
{
    public function index()
    {
        // Llama al backend Node.js
        $response = Http::get('http://localhost:3000/mantenimientos');

        if ($response->successful()) {
            $mantenimientos = $response->json();
            return view('mantenimientos.index', compact('mantenimientos'));
        } else {
            return view('mantenimientos.index')->withErrors(['error' => 'Error al obtener los mantenimientos']);
        }
    }
}