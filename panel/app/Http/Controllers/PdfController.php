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
}
