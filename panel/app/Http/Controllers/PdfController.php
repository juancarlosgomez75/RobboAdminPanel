<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class PdfController extends Controller
{
    public function generatePdf()
    {
        // Cargar una vista y pasarle datos
        $data = ['name' => 'Juan', 'age' => 25];
        
        $pdf = Pdf::loadView('pdf_template', $data);
        
        // Descargar el PDF generado
        // return $pdf->download('documento.pdf');
        return $pdf->stream('documento.pdf');
    }

    public function generateReport(Request $request)
    {
        // Accede a los datos enviados por POST
        $data = json_decode($request->input('data'),true);
    
        // Genera el PDF con los datos
        $pdf = Pdf::loadView('report_template', compact('data'));
    
        return $pdf->stream('reporte.pdf'); // Muestra el PDF en el navegador
    }


}
