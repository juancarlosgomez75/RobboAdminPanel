<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;
use App\Mail\EnviarReporte;

class ProcesarEnvioReportes implements ShouldQueue
{
    use Queueable;

    //Variables que usaré
    public $studies;
    public $userId;

    public function __construct($userId)
    {
        $this->userId = $userId;

        Cache::forget("reportSendProgress_".$userId);
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $total = count($this->studies);

        //Obtengo los estudios"reportResult_" . Auth::user()->id
        $data=Cache::get("reportResult_" . $this->userId,False);

        if($data==False){


            foreach($data as $index =>$datos){

                if(array_key_exists("ResultsReport", $datos)){
                    //Genero el reporte PDF
                    $pdfResponse = generateReportPDF($index);

                    //Genero la informacion
                    $infoReply=array_diff_key($datos, array_flip(['DetailedReport', 'ResultsReport']));

                    //Envio el correo
                    //$sendto=$datos["Email"];
                    $sendto="administracion@coolsofttechnology.com";

                    sleep(1);

                    Mail::to($sendto)->send(new EnviarReporte("Reporte por periodo", $pdfResponse,'administracion@coolsofttechnology.com',$infoReply ));
                }

                sleep(1);

                // Actualiza la barra de progreso (ej: en caché)
                Cache::put("reportSendProgress_".$this->userId, round((($index + 1) / $total) * 100), 600);
            }
        }else{
            Cache::put("reportSendProgress_".$this->userId, -100, 600);
        }


    }
}
