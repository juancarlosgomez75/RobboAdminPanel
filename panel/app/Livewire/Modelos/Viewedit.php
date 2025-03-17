<?php

namespace App\Livewire\Modelos;

use Livewire\Component;
use Illuminate\Support\Facades\Http;

class Viewedit extends Component
{
    //Variables que se cargan
    public $ModelInformation;
    public $ManagerInformation;
    public $StudyInformation;
    public $editing=false;
    public $alerta=false;

    public $alerta_sucess="";
    public $alerta_error="";
    public $alerta_warning="";

    //Inicializo la información de la modelo
    public $drivername;
    public $usecustomname=0;
    public $customname="";
    public $estudioactual=0;
    public $manageractual=0;
    public $active=false;
    public $paginas=[];

    public $paginasDisponibles=[];

    //Genero la variable de lista de managers y estudios
    public $listadoestudios;
    public $listadomanagers;

    public function validar(){
        $this->alerta_warning="";
        $this->alerta_error="";
        $this->alerta_sucess="";

        //Valido el nombre de usuario
        if(!(preg_match('/^[a-zA-Z0-9._-]+$/', $this->drivername) && !empty(trim($this->drivername)))){
            $this->alerta=true;
            $this->alerta_warning="Alerta: El nombre de usuario no es válido";
            return;
        }
        //Valido el nombre personalizado
        elseif (!empty(trim($this->customname)) && !preg_match('/^[a-zA-Z0-9._-]+$/', $this->customname)){
            $this->alerta=true;
            $this->alerta_warning="Alerta: El nombre personalizado no es válido";
            return;
        }
        //Valido si usa o no
        elseif(!($this->usecustomname=="0") && !($this->usecustomname=="1")){
            $this->alerta=true;
            $this->alerta_warning="No se ha indicado si usar o no el nombre personalizado";
            return;
        }

        //Valido si está en un estudio correcto
        $estudioencontrado=false;
        foreach($this->listadoestudios as $estudio){
            if($estudio["Id"]==$this->estudioactual){
                $estudioencontrado=true;
            }
        }

        if(!$estudioencontrado){
            $this->alerta=true;
            $this->alerta_warning="No se ha seleccionado un estudio válido";
            return;
        }

        //Valido si tengo un manager correcto
        $managerencontrado=false;
        foreach($this->listadomanagers as $manager){
            if($manager["Id"]==$this->manageractual){
                $managerencontrado=true;
            }
        }

        if(!$managerencontrado){
            $this->alerta=true;
            $this->alerta_warning="No se ha seleccionado un manager válido";
            return;
        }

        //Valido todas las páginas ingresadas
        foreach($this->paginas as $pagina){
            
            //Analizo el nickname
            if(!(preg_match('/^[a-zA-Z0-9._-]+$/', $pagina["NickName"]) && !empty(trim($pagina["NickName"])))){
                $this->alerta=true;
                $this->alerta_warning="Alerta: El nombre de usuario: ".$pagina["NickName"]." no es válido";
                return;
            }

            //Analizo el id de la página
            $paginaencontrada=false;
            foreach($this->paginasDisponibles as $pagbus){
                if($pagina["NickPage"]==$pagbus){
                    $paginaencontrada=true;
                }
            }

            if(!$paginaencontrada){
                $this->alerta=true;
                $this->alerta_warning="Alerta: La página: ".$pagina["NickPage"]." no es válida";
                return;
            }

        }

        return true;
    }

    public function guardarEdicion(){
        if($this->validar()){

            //Cargo la data
            $enviar=[
                'Branch' => 'Server',
                'Service' => 'SelfModels',
                'Action' => 'Edit',
                "Data"=>[
                    "UserId"=>"1",
                    "ModelData"=>[
                        "ModelId"=>$this->ModelInformation["ModelId"],
                        "ModelUserName"=>$this->drivername,
                        "ModelPersonalCm"=>($this->usecustomname == "1"),
                        "ModelPersonalCmName"=>$this->customname,
                        "ModelPages"=>$this->paginas
                    ],
                    "UserData"=>[
                        "Id"=>$this->manageractual
                    ]
                ],
            ];

            //Genero la modificación
            $response = Http::withHeaders([
                'Authorization' => 'AAAA'
            ])->withOptions([
                'verify' => false // Desactiva la verificación SSL
            ])->post(config('app.API_URL'), $enviar);


            $data = $response->json();

            if (isset($data['Status'])) {
                if($data['Status']){
                    $this->alerta=true;
                    $this->alerta_sucess= "Se ha modificado esta cuenta de forma correcta";
                    $this->editing=false;
                    
                    registrarLog("Producción","Modelos","Editar","Se ha editado al modelo #".$this->ModelInformation["ModelId"].", con información: ".json_encode($enviar),true);

                    return;

                }else{
                    $this->alerta=true;
                    $this->alerta_error= "Ha ocurrido un error durante la operación: ".($data['Error']??"Error no reportado");
                    registrarLog("Producción","Modelos","Editar","Se ha intentado editar al modelo #".$this->ModelInformation["ModelId"].", con información: ".json_encode($enviar),false);
                    return;
                }
            }

            $this->alerta=true;
            $this->alerta_error= "Ha ocurrido un error, contacte a soporte";
        }
    }

    public function nuevaPagina(){
        if($this->editing){
            $this->paginas[]=["NickName"=>"","NickPage"=>"-1"];
        }
        
    }

    public function eliminarPagina($index)
    {
        // $this->paginas[$index]["NickName"]="Index es: ".$index;
        if($this->editing){
            unset($this->paginas[$index]); // Elimina el elemento del array
            $this->paginas = array_values($this->paginas); // Reorganiza los índices
        }

    }
    
    public function activarEdicion(){
        $this->editing=true;

    }

    public function obtenerEstudios(){

        //Genero la petición para obtener la información
        $information = Http::withHeaders([
            'Authorization' => 'AAAA'
        ])->withOptions([
            'verify' => false // Desactiva la verificación SSL
        ])->post(config('app.API_URL'), [
            'Branch' => 'Server',
            'Service' => "GeneralParams",
            'Action' => "GeneralParams",
            'Data' => ["UserId" => "1"]
        ]);

        $generalinformation=$information->json();

        //Genero la petición de informacion
        $response = Http::withHeaders([
            'Authorization' => 'AAAA'
        ])->withOptions([
            'verify' => false // Desactiva la verificación SSL
        ])->post(config('app.API_URL'), [
            'Branch' => 'Server',
            'Service' => 'PlatformUser',
            'Action' => 'StudyList',
            'Data' => ["UserId" => "1"]
        ]);

        $data = $response->json();

        //Analizo si todo okay
        if(isset($data['Status']) && isset($generalinformation['Status'])){
            //Analizo si el status es okay
            if($data["Status"] && $generalinformation["Status"]){
                if(isset($data["ListStudyData"])){

                    // Mapear ciudades con su país
                    $cityMap = [];
                    if (isset($generalinformation['CountryList'])) {
                        foreach ($generalinformation['CountryList'] as $country) {
                            foreach ($country['Cities'] as $city) {
                                $cityMap[$city['Id']] = $city['CityName'] . ',' . $country['CountryName'];
                            }
                        }
                    }

                    // Agregar el campo City en ListStudyData
                    if (isset($data['ListStudyData'])) {
                        foreach ($data['ListStudyData'] as &$study) {
                            $study['FullName'] = $study['StudyName'].' ('.($cityMap[$study['CityId']] ?? 'Desconocido').')';
                        }
                    }


                    //Significa que está bien, entonces proceso la lista
                    $this->listadoestudios=$data["ListStudyData"];

                    usort($this->listadoestudios, function ($a, $b) {
                        return strcmp($a["FullName"], $b["FullName"]);
                    });

                    //Obtengo las páginas disponibles
                    $this->paginasDisponibles=$generalinformation['WebPagesList'];

                    return true;
                }
            }
        }

        $this->alerta=true;
        $this->alerta_error="Error obteniendo los estudios";
        return false;
    }

    public function obtenerManagers($idestudio){
        //Genero la petición de informacion
        $response = Http::withHeaders([
            'Authorization' => 'AAAA'
        ])->withOptions([
            'verify' => false // Desactiva la verificación SSL
        ])->post(config('app.API_URL'), [
            'Branch' => 'Server',
            'Service' => 'PlatformUser',
            'Action' => 'StudyInfo',
            'Data' => ["UserId" => "1"],
            "DataStudy"=> ["Id"=> $idestudio]
        ]);

        $data = $response->json();

        //Analizo si todo okay
        if(isset($data["Status"])){
            //Analizo si el status es okay
            if($data["Status"]){

                if(isset($data["ListUserData"])){
                    //Significa que está bien, entonces proceso la lista
                    $this->listadomanagers=$data["ListUserData"];

                    usort($this->listadomanagers, function ($a, $b) {
                        return strcmp($a["Name"], $b["Name"]);
                    });

                    return true;
                }
            }
        }

        $this->alerta=true;
        $this->alerta_error="Error obteniendo los managers";
        return false;
    }

    public function updatedestudioactual($valor)
    {
        $this->listadomanagers=[];
        $this->manageractual=0;
        $this->obtenerManagers($this->estudioactual);
    }

    public function mount($ModelInformation,$ManagerInformation,$StudyInformation){
        //Cargo la información principal
        $this->ModelInformation = $ModelInformation;
        $this->ManagerInformation = $ManagerInformation;
        $this->StudyInformation = $StudyInformation;

        //Cargo la información de la modelo
        $this->drivername = $ModelInformation["ModelUserName"];

        $this->usecustomname = ($ModelInformation["ModelPersonalCm"] ?? false) ? 1 : 0;
        $this->customname = $ModelInformation["ModelPersonalCmName"]??"";
        $this->active= $ModelInformation["ModelActive"];
        $this->estudioactual = $StudyInformation["Id"];
        $this->manageractual = $ManagerInformation["Id"];
        $this->paginas=$ModelInformation["ModelPages"];

        //Trato de obtener los estudios
        if($this->obtenerEstudios()){
            //Trato de obtener los managers
            if($this->obtenerManagers($this->estudioactual)){

            }
        }

    }
    public function render()
    {
        return view('livewire.modelos.viewedit');
    }
}
