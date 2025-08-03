<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session; // ¡Falta esta línea!
use Illuminate\Support\Facades\Redirect; // ¡Y esta otra!

class AuthController extends Controller
{
    public function showLoginForm(){
        return view('login');
    }

    public function login(Request $request)
    {
        if ($request->input('success')) {
            Session::put('authenticated', true);
            Session::put('usuario', $request->input('usuario'));

            return response()->json(['success' => true]);

        } else {
            return response()->json(['success' => false, 'error' => 'Fallo en la señal de autenticación'], 401);
        }
    }

    public function logout(Request $request)
    {
        Session::forget('authenticated');
        return Redirect::to('/login');
    }
}
