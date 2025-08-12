<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use App\Models\Parque; // Importamos el modelo Parque para acceder a la base de datos

class ArchivosController extends Controller
{
    /**
     * Muestra el formulario para subir archivos.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        try {
            // Obtenemos todos los parques directamente de la base de datos
            $parques = Parque::all(); 
            
            // Retornamos la vista y le pasamos la colección de parques completa
            return view('archivos.create', compact('parques'));
        } catch (\Exception $e) {
            // Manejamos el error en caso de que no se pueda conectar a la base de datos
            Log::error('Error al obtener parques en ArchivosController: ' . $e->getMessage());
            // Si hay un error, pasamos una colección vacía para que la vista no falle
            $parques = collect([]);
            return view('archivos.create', compact('parques'))->with('error', 'Error al conectar con la base de datos.');
        }
    }

    /**
     * Almacena el archivo subido en el sistema de archivos del servidor.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        // 1. Validar los datos del formulario, incluyendo los archivos
        $request->validate([
            'documentos' => 'required|array',
            'documentos.*' => 'file|mimes:jpeg,png,pdf,doc,docx|max:5120',
            'cod_parque' => 'required|integer' 
        ]);

        $parqueId = $request->input('cod_parque');
        
        try {
            // Aquí puedes usar la lógica para subir archivos a un almacenamiento local o a un servicio como S3
            // o seguir usando la llamada a la API si es necesaria
            // En este ejemplo, mantendremos la lógica de la API que tenías
            $requestApi = Http::asMultipart();

            // 2. Adjuntar todos los archivos a la petición
            foreach ($request->file('documentos') as $archivo) {
                $requestApi->attach(
                    'documentos[]', 
                    file_get_contents($archivo),
                    $archivo->getClientOriginalName()
                );
            }

            // 3. Enviar la petición a tu API
            $response = $requestApi->post("http://localhost:3000/parques/{$parqueId}/archivos");

            if ($response->successful()) {
                return back()->with('success', 'Archivo subido correctamente a la API.');
            } else {
                Log::error('API Error al subir archivo: ' . $response->status() . ' - ' . $response->body());
                return back()->with('error', 'No se pudo subir el archivo a la API.');
            }
        } catch (\Exception $e) {
            Log::error('Error de conexión al subir archivo a la API: ' . $e->getMessage());
            return back()->with('error', 'Error al conectar con la API.');
        }
    }
}

