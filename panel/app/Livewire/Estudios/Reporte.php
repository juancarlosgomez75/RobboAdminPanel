<?php

namespace App\Livewire\Estudios;

use Livewire\Component;

class Reporte extends Component
{
    public $informacion;

    public $estudioSeleccionado;
    public $resultadoObtenido=false;
    public $resultado;

    //Variables del registro
    public $fechaInicio;
    public $fechaFin;

    public function mount($information){
        $this->informacion=$information;

        usort($this->informacion, function ($a, $b) {
            return strcmp($a["StudyName"], $b["StudyName"]);
        });
    }

    public function generarReporte(){
        $data_send=[
            'Branch' => 'Server',
            'Service' => 'PlatformUser',
            'Action' => 'StudyReport',
            'Data' => [
                "UserId" => "1",
                "ModelData"=>[
                    "InitialDate"=>"2024-04-01 00:00",
                    "FinalDate"=>"2024-04-16 00:00"
                ],
                "UserData"=>[
                    "Id"=>$this->estudioSeleccionado
                ]
            ]
        ];
        //Obtengo la informacion que requiero
        $data=sendBack($data_send);

        //Analizo si el status es true
        if($data["Status"]??False){
            if($data["Data"]["DetailedReport"]?? False){
                $raw=$data["Data"]["DetailedReport"];
                if(!empty($raw)){
                    //Defino los que almacena
                    $this->resultado=[
                        "Acciones"=>[],
                        "Paginas"=>[],
                        "Modelos"=>[],
                        "Tokens"=>0
                    ];
                    foreach($raw as $log){
                        $modelo=$log["ModelData"]["ModelUserName"];
                        //Analiso si ya tengo registrado al modelo o no
                        if(!array_key_exists($modelo, $this->resultado["Modelos"])){
                            $this->resultado["Modelos"][$modelo]=[
                                "Acciones"=>[],
                                "Paginas"=>[],
                                "Tokens"=>0
                            ];
                        }

                        //Analizo las acciiones generales, si no existte la creo, y si no le sumo
                        $accion=$log["Action"];
                        if(!array_key_exists($accion, $this->resultado["Acciones"])){
                            $this->resultado["Acciones"][$accion]=[
                                "Cantidad"=>0,
                                "Tiempo"=>0
                            ];
                        }
                        if(!array_key_exists($accion, $this->resultado["Modelos"][$modelo]["Acciones"])){
                            $this->resultado["Modelos"][$modelo]["Acciones"][$accion]=[
                                "Cantidad"=>0,
                                "Tiempo"=>0
                            ];
                        }

                        //Ahora aumento en acciones
                        $this->resultado["Acciones"][$accion]["Cantidad"]+=1;
                        $this->resultado["Acciones"][$accion]["Tiempo"]+=$log["ContratedValue"];

                        $this->resultado["Modelos"][$modelo]["Acciones"][$accion]["Cantidad"]+=1;
                        $this->resultado["Modelos"][$modelo]["Acciones"][$accion]["Tiempo"]+=$log["ContratedValue"];

                        //Ahora a las páginas, inicio preguntando si es simulador o cual
                        $pagina = empty($log["Page"]) ? "SIMULADOR" : $log["Page"];
                        if(!array_key_exists($pagina , $this->resultado["Paginas"])){
                            $this->resultado["Paginas"][$pagina]=[
                                "Tokens"=>0,
                                "Tipers"=>[]
                            ];
                        }
                        if(!array_key_exists($pagina , $this->resultado["Modelos"][$modelo]["Paginas"])){
                            $this->resultado["Modelos"][$modelo]["Paginas"][$pagina]=[
                                "Tokens"=>0,
                                "Tipers"=>[]
                            ];
                        }

                        //Ahora registro los tokens al total
                        $this->resultado["Tokens"]+=$log["Tokens"];
                        $this->resultado["Modelos"][$modelo]["Tokens"]+=$log["Tokens"];

                        //Ahora por pagina
                        $this->resultado["Paginas"][$pagina]["Tokens"]+=$log["Tokens"];
                        $this->resultado["Modelos"][$modelo]["Paginas"][$pagina]["Tokens"]+=$log["Tokens"];

                        //Ahora los tipers
                        $tiper = $log["Typer"];
                        if(!array_key_exists($tiper , $this->resultado["Paginas"][$pagina]["Tipers"])){
                            $this->resultado["Paginas"][$pagina]["Tipers"][$tiper]=[
                                "Tokens"=>0
                            ];
                        }
                        if(!array_key_exists($tiper , $this->resultado["Modelos"][$modelo]["Paginas"][$pagina]["Tipers"])){
                            $this->resultado["Modelos"][$modelo]["Paginas"][$pagina]["Tipers"][$tiper]=[
                                "Tokens"=>0
                            ];
                        }

                        //Sumo
                        $this->resultado["Paginas"][$pagina]["Tipers"][$tiper]["Tokens"]+=$log["Tokens"];
                        $this->resultado["Modelos"][$modelo]["Paginas"][$pagina]["Tipers"][$tiper]["Tokens"]+=$log["Tokens"];


                    }
                }

            }

            // //Ahora envio a Python a procesarla
            //     $this->resultado=sendBackPython([
            //         "Action"=>"ProcessData",
            //         "Data"=>$data["Data"]["DetailedReport"]
            //     ]);
        }


    }

    public function render()
    {
        return view('livewire.estudios.reporte');
    }
}
