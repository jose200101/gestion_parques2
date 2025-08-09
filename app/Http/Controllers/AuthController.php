<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;

class AuthController extends Controller
{
    public function showLoginForm(){
        return view('login');
    }

    public function login(Request $request)
    {
        if ($request->input('success')) {
            Session::put('authenticated', true);
            
            // Obtener los datos del usuario
            $usuario = $request->input('usuario');
            
            // Si $usuario es un array (objeto JSON), extraemos el nombre_usuario
            if (is_array($usuario) && isset($usuario['nombre_usuario'])) {
                Session::put('usuario', $usuario['nombre_usuario']);
                Session::put('usuario_completo', $usuario);
            } elseif (is_string($usuario)) {
                Session::put('usuario', $usuario);
            } else {
                return response()->json(['success' => false, 'error' => 'Formato de usuario inválido'], 400);
            }

            return response()->json(['success' => true]);

        } else {
            return response()->json(['success' => false, 'error' => 'Fallo en la señal de autenticación'], 401);
        }
    }

    public function logout(Request $request)
    {
        Session::forget('authenticated');
        Session::forget('usuario');
        Session::forget('usuario_completo');
        return Redirect::to('/login');
    }
}
