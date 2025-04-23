<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Auth;

class ProcesarConsultaReportes implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    //Variables que usaré
    public $studies;
    public $fechaInicio;
    public $fechaFin;
    public $tipo;
    public function __construct($studies)
    {
        $this->studies=$studies;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $total = count($this->studies);

        foreach($this->studies as $index =>$study){
            //Cargo la info cruda
            $raw=$study["Data"]["DetailedReport"];
            $resultado_estudio=[];

            if(!empty($raw)){
                //Defino los que almacena
                $resultado_estudio=[
                    "Acciones"=>[],
                    "Paginas"=>[],
                    "Modelos"=>[],
                    "Tokens"=>0
                ];
                foreach($raw as $log){
                    $modelo=$log["ModelData"]["ModelUserName"];
                    //Analiso si ya tengo registrado al modelo o no
                    if(!array_key_exists($modelo, $resultado_estudio["Modelos"])){
                        $resultado_estudio["Modelos"][$modelo]=[
                            "Acciones"=>[],
                            "Paginas"=>[],
                            "Tokens"=>0
                        ];
                    }

                    //Analizo las acciiones generales, si no existte la creo, y si no le sumo
                    $accion=$log["Action"];
                    if(!array_key_exists($accion, $resultado_estudio["Acciones"])){
                        $resultado_estudio["Acciones"][$accion]=[
                            "Cantidad"=>0,
                            "Tiempo"=>0
                        ];
                    }
                    if(!array_key_exists($accion, $resultado_estudio["Modelos"][$modelo]["Acciones"])){
                        $resultado_estudio["Modelos"][$modelo]["Acciones"][$accion]=[
                            "Cantidad"=>0,
                            "Tiempo"=>0
                        ];
                    }

                    //Ahora aumento en acciones
                    $resultado_estudio["Acciones"][$accion]["Cantidad"]+=1;
                    $resultado_estudio["Acciones"][$accion]["Tiempo"]+=$log["ContratedValue"];

                    $resultado_estudio["Modelos"][$modelo]["Acciones"][$accion]["Cantidad"]+=1;
                    $resultado_estudio["Modelos"][$modelo]["Acciones"][$accion]["Tiempo"]+=$log["ContratedValue"];

                    //Ahora a las páginas, inicio preguntando si es simulador o cual
                    $pagina = empty($log["Page"]) ? "SIMULADOR" : $log["Page"];
                    if(!array_key_exists($pagina , $resultado_estudio["Paginas"])){
                        $resultado_estudio["Paginas"][$pagina]=[
                            "Tokens"=>0,
                            "Tipers"=>[]
                        ];
                    }
                    if(!array_key_exists($pagina , $resultado_estudio["Modelos"][$modelo]["Paginas"])){
                        $resultado_estudio["Modelos"][$modelo]["Paginas"][$pagina]=[
                            "Tokens"=>0,
                            "Tipers"=>[]
                        ];
                    }

                    //Ahora registro los tokens al total
                    $resultado_estudio["Tokens"]+=$log["Tokens"];
                    $resultado_estudio["Modelos"][$modelo]["Tokens"]+=$log["Tokens"];

                    //Ahora por pagina
                    $resultado_estudio["Paginas"][$pagina]["Tokens"]+=$log["Tokens"];
                    $resultado_estudio["Modelos"][$modelo]["Paginas"][$pagina]["Tokens"]+=$log["Tokens"];

                    //Ahora los tipers
                    $tiper = $log["Typer"];
                    if(!array_key_exists($tiper , $resultado_estudio["Paginas"][$pagina]["Tipers"])){
                        $resultado_estudio["Paginas"][$pagina]["Tipers"][$tiper]=[
                            "Tokens"=>0
                        ];
                    }
                    if(!array_key_exists($tiper , $resultado_estudio["Modelos"][$modelo]["Paginas"][$pagina]["Tipers"])){
                        $resultado_estudio["Modelos"][$modelo]["Paginas"][$pagina]["Tipers"][$tiper]=[
                            "Tokens"=>0
                        ];
                    }

                    //Sumo
                    $resultado_estudio["Paginas"][$pagina]["Tipers"][$tiper]["Tokens"]+=$log["Tokens"];
                    $resultado_estudio["Modelos"][$modelo]["Paginas"][$pagina]["Tipers"][$tiper]["Tokens"]+=$log["Tokens"];


                }
            }

            //Ahora modifico
            unset($this->studies[$study]["Data"]["DetailedReport"]);
            $this->studies[$study]["Data"]["ResultsReport"]=$resultado_estudio;

            // Actualiza la barra de progreso (ej: en caché)
            Cache::put("reportProgress_".Auth::user()->id, round((($index + 1) / $total) * 100), 600);
        }

        // Guarda los resultados finales
        Cache::put("reportResult_".Auth::user()->id, $this->studies, 600);
    }
}
