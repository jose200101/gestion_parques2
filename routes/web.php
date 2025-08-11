<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UsuariosController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ParquesController; //Ana R.Cabrera
use App\Http\Controllers\MantenimientoController;
use App\Http\Controllers\ArchivosController; // Ana


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

    // Rutas para la gestión de usuarios
    Route::get('/usuarios', [UsuariosController::class, 'index'])->name('usuarios.index');

    // Ana R. Cabrera - Usamos 'Route::resource' para todas las rutas del controlador de parques.
    // Esto incluye: index, create, store, show, edit, update, y destroy.
    Route::resource('parques', ParquesController::class);

    // Ana R. Cabrera: Rutas para la gestión de archivos.
    // La ruta GET maneja la visualización del formulario para subir archivos.
    Route::get('archivos/create', [ArchivosController::class, 'create'])->name('archivos.create');

    // La ruta POST procesa el envío del formulario de archivos y los almacena.
    Route::post('archivos', [ArchivosController::class, 'store'])->name('archivos.store');
    
    // Rutas para vistas específicas
    Route::view('/invitado', 'invitado.index')->name('invitado');

    // Ruta de Logout
    // Esta ruta invalida la sesión y redirige al login
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    //Ruta usuarios y vista con tabla + modal
    Route::get('/usuarios', [UsuariosController::class, 'index'])->name('usuarios.index');
    Route::post('/usuarios', [UsuariosController::class, 'store'])->name('usuarios.store');
    Route::post('/usuarios/actualizar', [UsuariosController::class, 'update'])->name('usuarios.update');
    Route::delete('/usuarios/{id}', [UsuariosController::class, 'destroy'])->name('usuarios.destroy');
    Route::get('/usuarios/dashboard', [App\Http\Controllers\UsuariosController::class, 'dashboard']);

    //Ruta vista del perfil
    Route::get('/perfil', [UsuariosController::class, 'perfil'])->name('perfil');

    // Rutas para cambio de contraseña
    Route::get('/cambiar-password', [UsuariosController::class, 'mostrarCambioPassword'])->name('usuarios.cambio-password');
    Route::post('/cambiar-password', [UsuariosController::class, 'cambiarPassword'])->name('usuarios.cambiar-password');

});

//RUTAS CONFIGURACION ANDRÉS
// Rutas para las vistas de los campos de configuración
Route::get('/configuracion/panel_configuracion', function () {
    return view('configuracion.panel_configuracion');
})->name('configuracion.panel_configuracion');

Route::get('/configuracion/logs_sistema', function () {
    return view('configuracion.logs_sistema');
})->name('configuracion.logs_sistema');

Route::get('/configuracion/admin_backups', function () {
    return view('configuracion.admin_backups');
})->name('configuracion.admin_backups');
//RUTAS CONFIGURACION ANDRÉS

// Ruta del listado de Mantenimientos
Route::get('/mantenimientos/create', function () {
    return view('mantenimientos.create'); // <-- Asegúrate de tener esta vista
})->name('mantenimientos.create');

//Ruta de Mantenimientos Carlos.
Route::get('/mantenimientos', [MantenimientoController::class, 'index'])->name('mantenimientos.index');

// Ruta del listado de Recursos
Route::get('/recursos', function () {
    return view('recursos.index');
})->name('recursos.index');
