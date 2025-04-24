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
        $title = $request->input('title');
        $otherData = $request->input('otherData'); // Si tienes más datos
    
        // Genera el PDF con los datos
        $pdf = Pdf::loadView('report_template', compact('title', 'otherData'));
    
        return $pdf->stream('reporte.pdf'); // Muestra el PDF en el navegador
    }


}
