<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

use function PHPUnit\Framework\isFalse;

class ProcesarConsultaReportes implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    //Variables que usaré
    public $studies;
    public $fechaInicio;
    public $fechaFin;
    public $userId;
    public $API_PROD=False;

    public $tipo;
    public function __construct($userId,$studies,$fechaInicio,$fechaFin,$API_URL=False)
    {
        $this->userId = $userId;
        $this->studies=$studies;
        $this->fechaInicio=$fechaInicio;
        $this->fechaFin=$fechaFin;

        if($API_URL=="production"){
            $this->API_PROD=True;
        }
        

        Cache::forget("reportProgress_".$userId);
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // for ($i = 0; $i <= 10; $i++) {
        //     // Simula trabajo de 1 segundo
        //     sleep(1);

        //     // Actualiza progreso en %
        //     $progreso = $i * 10;
        //     Cache::put("reportProgress_".$this->userId, $progreso, 600);
        // }

        $total = count($this->studies);

        foreach($this->studies as $index =>$study){

            $data_send=[
                'Branch' => 'Server',
                'Service' => 'PlatformUser',
                'Action' => 'StudyReport',
                'Data' => [
                    "UserId" => "1",
                    "ModelData"=>[
                        "InitialDate"=>$this->fechaInicio,
                        "FinalDate"=>$this->fechaFin
                    ],
                    "UserData"=>[
                        "Id"=>$study["Id"]
                    ]
                ]
            ];
            //Obtengo la informacion que requiero
            $data=sendBack($data_send,"AAA",$this->API_PROD);

            if($data["Status"]??False){
                if($data["Data"]["DetailedReport"]?? False){
                    //Cargo la info cruda
                    $raw=$data["Data"]["DetailedReport"];
                    $resultado_estudio=[];

                    if(!empty($raw)){
                        //Defino lo que almacena
                        $resultado_estudio=[
                            "Maquinas"=>[],
                            "Acciones"=>[],
                            "Paginas"=>[],
                            "Modelos"=>[],
                            "Tokens"=>0
                        ];

                    }
                    //Añado el detailed
                    $this->studies[$index]["DetailedReport"]=$raw;
                    foreach($raw as $log){
                        $maquina=$log["Machine"]["FirmwareID"];
                        //Analizo si ya tengo registrada la maquina o no
                        if(!array_key_exists($maquina, $resultado_estudio["Maquinas"])){
                            $resultado_estudio["Maquinas"][$maquina]=[
                                "Acciones"=>[],
                                "Modelos"=>[],
                                "Tokens"=>0
                            ];
                        }

                        $modelo=$log["ModelData"]["ModelUserName"];
                        //Analizo si ya tengo registrado al modelo o no
                        if(!array_key_exists($modelo, $resultado_estudio["Modelos"])){
                            $resultado_estudio["Modelos"][$modelo]=[
                                "Acciones"=>[],
                                "Paginas"=>[],
                                "Tokens"=>0
                            ];
                        }

                        if(!array_key_exists($modelo, $resultado_estudio["Maquinas"][$maquina]["Modelos"])){
                            $resultado_estudio["Maquinas"][$maquina]["Modelos"][$modelo]=[
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
                        if(!array_key_exists($accion, $resultado_estudio["Maquinas"][$maquina]["Acciones"])){
                            $resultado_estudio["Maquinas"][$maquina]["Acciones"][$accion]=[
                                "Cantidad"=>0,
                                "Tiempo"=>0
                            ];
                        }

                        //Ahora aumento en acciones
                        $resultado_estudio["Acciones"][$accion]["Cantidad"]+=1;
                        $resultado_estudio["Acciones"][$accion]["Tiempo"]+=$log["ContratedValue"];

                        $resultado_estudio["Modelos"][$modelo]["Acciones"][$accion]["Cantidad"]+=1;
                        $resultado_estudio["Modelos"][$modelo]["Acciones"][$accion]["Tiempo"]+=$log["ContratedValue"];

                        $resultado_estudio["Maquinas"][$maquina]["Acciones"][$accion]["Cantidad"]+=1;
                        $resultado_estudio["Maquinas"][$maquina]["Acciones"][$accion]["Tiempo"]+=$log["ContratedValue"];

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

                        $resultado_estudio["Maquinas"][$maquina]["Tokens"]+=$log["Tokens"];
                        $resultado_estudio["Maquinas"][$maquina]["Modelos"][$modelo]["Tokens"]+=$log["Tokens"];

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

                    //Ahora modifico
                    $this->studies[$index]["ResultsReport"]=$resultado_estudio;
                }

            }

            // sleep(1);

            // Actualiza la barra de progreso (ej: en caché)
            Cache::put("reportProgress_".$this->userId, round((($index + 1) / $total) * 100), 600);

        }

        Cache::put("reportResult_".$this->userId,  $this->studies, 600);
        // Cache::forget("reportProgress_".$this->userId);

    }
}
