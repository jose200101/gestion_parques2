<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MapaController extends Controller
{
    public function mostrarMapa()
    {
        return view('Mapa.mapa');
    }
}