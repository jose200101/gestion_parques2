<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ReporteController extends Controller
{
    public function eventosAmbientales()
    {
        // La URL de tu API DEBE COINCIDIR con el endpoint de tu código de Node.js
        $response = Http::get('http://localhost:3000/eventos_ambientales');

        if ($response->successful()) {
            $eventos = $response->json();
            return view('reportes.eventos-ambientales', ['eventos' => $eventos]);
        } else {
            return view('reportes.eventos-ambientales', ['eventos' => []])
                ->with('error', 'No se pudieron obtener los datos de la API.');
        }
    }
}
