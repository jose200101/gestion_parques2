<?php

namespace App\Http\Controllers;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\EventoAmbiental;

class EventoAmbientalController extends Controller
{
    public function exportarPdf(Request $request)
    {
        // Lógica para obtener los datos.
        // Esto es un ejemplo, debes adaptarlo a tu lógica de filtrado.
        $query = EventoAmbiental::query();
        
        if ($request->has('filtro_nombre')) {
            $query->where('nombre', 'like', '%' . $request->input('filtro_nombre') . '%');
        }

        $eventos = $query->get();

        // Cargar la vista Blade con los datos.
        $pdf = PDF::loadView('pdf.eventos', compact('eventos'));

        // Opción 1: Descargar el PDF
        // return $pdf->download('eventos-ambientales.pdf');

        // Opción 2: Enviar por correo electrónico
        Mail::send('emails.eventos_pdf', ['data' => 'contenido'], function ($message) use ($pdf) {
            $message->to('destinatario@example.com', 'Nombre Destinatario')
                    ->subject('Listado de Eventos Ambientales')
                    ->attachData($pdf->output(), 'eventos-ambientales.pdf');
        });

        return back()->with('success', 'El PDF ha sido enviado por correo electrónico.');
    }
}
