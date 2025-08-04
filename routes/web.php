<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UsuariosController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ParquesController; //Ana R.Cabrera


// Ruta para mostrar el formulario de login
// Con el nombre 'login', que es usado por el middleware para las redirecciones
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');

// Ruta para procesar la petición POST de tu JavaScript
// Se le da el nombre 'login.post' que usa tu JavaScript para comunicarse con Laravel
Route::post('/login', [AuthController::class, 'login'])->name('login.post');

// Grupo de rutas protegidas por el middleware 'api_auth'
// Las rutas dentro de este grupo solo serán accesibles si el usuario tiene una sesión activa
Route::middleware('api_auth')->group(function () {
    // La ruta principal, que redirige a 'welcome'
    Route::get('/', function () {
        return view('welcome');
    })->name('home'); // Asigno el nombre 'home' para referencia futura

    // Rutas para la gestión de parques (Ana R. Cabrera)
    Route::resource('parques', ParquesController::class);

    // Rutas para la gestión de usuarios
    Route::get('/usuarios', [UsuariosController::class, 'index'])->name('usuarios.index');
    
    // Rutas para vistas específicas
    Route::view('/invitado', 'invitado.index')->name('invitado');

    // Ruta de Logout
    // Esta ruta invalida la sesión y redirige al login
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});