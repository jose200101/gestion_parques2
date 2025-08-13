<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use PDF; // Importa la clase PDF
use Log; // Importa la clase Log para depuración

class EmailController extends Controller
{
    public function sendReport(Request $request)
    {
        // 1. Validar la solicitud del cliente
        $request->validate([
            'recipient' => 'required|email',
            'data' => 'required|string', // Ahora es un string JSON
            'headers' => 'required|string', // También recibimos los encabezados
        ]);
        
        $tableDataJson = $request->input('data');
        $tableHeadersJson = $request->input('headers');
        $recipient = $request->input('recipient');

        // 2. Decodificar los datos JSON para obtener los datos de la tabla y los encabezados
        $rows = json_decode($tableDataJson, true);
        $headers = json_decode($tableHeadersJson, true); // Decodificamos también los encabezados

        // Preparar los datos para la vista del PDF, incluyendo los encabezados
        // Si tu vista pdf.eventos espera solo las filas, puedes adaptar aquí
        // Por ejemplo, puedes pasar $headers y $rows por separado o combinarlos si la vista lo requiere.
        // En tu vista actual (pdf/eventos.blade.php), se asume que $data ya incluye todo.
        // Si $rows es solo el cuerpo, tu vista podría necesitar los encabezados aparte.
        
        // Si tu vista espera $data como un array de arrays, donde la primera fila son los headers,
        // podrías hacer esto (pero es mejor pasarlos por separado si la vista está diseñada para ello):
        $pdfData = [];
        // Añadir encabezados como la primera fila si la vista los necesita en $data
        // foreach ($headers as $header) {
        //     $pdfData[0][] = $header['title'] ?? $header; // Asume que headers puede ser un array de objetos o strings
        // }
        // foreach ($rows as $row) {
        //     $pdfData[] = $row;
        // }

        // Para tu vista `pdf/eventos.blade.php`, que espera `$data` como un array de filas directamente:
        // Asegúrate de que los encabezados no se incluyan si tu vista no los necesita en `$data` iterado.
        // El frontend ya no envía los encabezados dentro de `tableData.body`, sino por separado.
        // Así que tu vista pdf.eventos.blade.php debería ser modificada para usar $headers y $rows por separado.
        
        // VOY A ASUMIR QUE TU VISTA `pdf/eventos.blade.php` NECESITA UN ARRAY DE ARRAYS PARA `$data`
        // Y QUE LOS ENCABEZADOS ESTÁN FIJOS O LOS MANEJAS DE OTRA FORMA EN LA VISTA DEL PDF.
        // Si no es así, necesitarás ajustar la vista y la forma en que pasas los datos.
        
        // Si la vista PDF espera una lista de filas, tal como la estás pasando desde el frontend:
        $dataForPdf = $rows;


        // 3. Generar el PDF a partir de la vista
        try {
            // Asegúrate de que el nombre de la vista sea correcto ('pdf.eventos' o 'pdf.report')
            $pdf = PDF::loadView('pdf.eventos', ['data' => $dataForPdf]);
        } catch (\Exception $e) {
            Log::error('Error al generar el PDF: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Error al generar el PDF.']);
        }
        
        // 4. Enviar el correo con el PDF adjunto
        try {
            Mail::send([], [], function ($message) use ($recipient, $pdf) {
                $message->to($recipient)
                        ->subject('Reporte de Eventos Ambientales')
                        ->attachData($pdf->output(), 'reporte_eventos.pdf', [
                            'mime' => 'application/pdf',
                        ]);
            });

            return response()->json(['success' => true, 'message' => 'Correo enviado con éxito.']);
        } catch (\Exception $e) {
            Log::error('Error al enviar el correo: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Error al enviar el correo: ' . $e->getMessage()]);
        }
    }
}
