<?php

namespace App\Livewire\Modelos;

use Livewire\Component;
use Illuminate\Support\Facades\Http;

class Create extends Component
{

    //Inicializo la información de la modelo
    public $drivername;
    public $usecustomname=0;
    public $customname="";
    public $estudioactual=0;
    public $manageractual=0;
    public $active=false;
    public $paginas=[];

    //Genero la variable de lista de managers y estudios
    public $listadoestudios;
    public $listadomanagers;

    public $paginasDisponibles=[];

    public function validar(){

        //Valido el nombre de usuario
        if(!(preg_match('/^[a-zA-Z0-9._-]+$/', $this->drivername) && !empty(trim($this->drivername)))){

            $this->dispatch('mostrarToast', 'Crear modelo', "Alerta: El nombre de usuario no es válido");
            return;
        }
        //Valido el nombre personalizado
        elseif (!empty(trim($this->customname)) && !preg_match('/^[a-zA-Z0-9._-]+$/', $this->customname)){
            $this->dispatch('mostrarToast', 'Crear modelo', "Alerta: El nombre personalizado no es válido");
            return;
        }
        //Valido si usa o no
        elseif(!($this->usecustomname=="0") && !($this->usecustomname=="1")){
            $this->dispatch('mostrarToast', 'Crear modelo', "Alerta: No se ha indicado si usar o no el nombre personalizado");
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
            $this->dispatch('mostrarToast', 'Crear modelo', "Alerta: Estudio no válido");
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
            $this->dispatch('mostrarToast', 'Crear modelo', "Alerta: No se ha seleccionado un manager válido");
            return;
        }

        //Valido todas las páginas ingresadas
        foreach($this->paginas as $pagina){
            
            //Analizo el nickname
            if(!(preg_match('/^[a-zA-Z0-9._-]+$/', $pagina["NickName"]) && !empty(trim($pagina["NickName"])))){

                $this->dispatch('mostrarToast', 'Crear modelo', "Alerta: El nombre de usuario: ".$pagina["NickName"]." no es válido");
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

                $this->dispatch('mostrarToast', 'Crear modelo', "Alerta: La página: ".$pagina["NickPage"]." no es válida");
                return;
            }

        }

        return true;
    }

    public function guardar(){
        if($this->validar()){

            //Cargo la data
            $enviar=[
                'Branch' => 'Server',
                'Service' => 'SelfModels',
                'Action' => 'Create',
                "Data"=>[
                    "UserId"=>"1",
                    "ModelData"=>[
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

            // //Genero la modificación
            // $response = Http::withHeaders([
            //     'Authorization' => 'AAAA'
            // ])->withOptions([
            //     'verify' => false // Desactiva la verificación SSL
            // ])->post(config('app.API_URL'), $enviar);


            // $data = $response->json();

            $data=sendBack($enviar);

            if (isset($data['Status'])) {
                if($data['Status']){
                    $this->resetExcept("listadoestudios","paginasdisponibles");

                    $this->dispatch('mostrarToast', 'Crear modelo', "Se ha registrado al modelo de forma correcta");
                    
                    registrarLog("Producción","Modelos","Crear","Se ha registrado al modelo con información: ".json_encode($enviar),true);

                    return;

                }else{

                    $this->dispatch('mostrarToast', 'Crear modelo', "Ha ocurrido un error durante la operación: ".($data['Error']??"Error no reportado"));
                    registrarLog("Producción","Modelos","Crear","Se ha intentado registrar al con información: ".json_encode($enviar),false);
                    return;
                }
            }

            $this->dispatch('mostrarToast', 'Crear modelo', "Ha ocurrido un error durante la operación, contacte a soporte");
        }
    }


    public function nuevaPagina(){
        $this->paginas[]=["NickName"=>"","NickPage"=>"-1"];
        
    }

    public function eliminarPagina($index)
    {
        unset($this->paginas[$index]); // Elimina el elemento del array
        $this->paginas = array_values($this->paginas); // Reorganiza los índices
    }

    public function obtenerEstudios(){

        // //Genero la petición para obtener la información
        // $information = Http::withHeaders([
        //     'Authorization' => 'AAAA'
        // ])->withOptions([
        //     'verify' => false // Desactiva la verificación SSL
        // ])->post(config('app.API_URL'), [
        //     'Branch' => 'Server',
        //     'Service' => "GeneralParams",
        //     'Action' => "GeneralParams",
        //     'Data' => ["UserId" => "1"]
        // ]);

        // $generalinformation=$information->json();

        $data_send=[
            'Branch' => 'Server',
            'Service' => "GeneralParams",
            'Action' => "GeneralParams",
            'Data' => ["UserId" => "1"]
        ];
        $generalinformation=sendBack($data_send);

        // //Genero la petición de informacion
        // $response = Http::withHeaders([
        //     'Authorization' => 'AAAA'
        // ])->withOptions([
        //     'verify' => false // Desactiva la verificación SSL
        // ])->post(config('app.API_URL'), [
        //     'Branch' => 'Server',
        //     'Service' => 'PlatformUser',
        //     'Action' => 'StudyList',
        //     'Data' => ["UserId" => "1"]
        // ]);

        // $data = $response->json();

        $data_send=[
            'Branch' => 'Server',
            'Service' => 'PlatformUser',
            'Action' => 'StudyList',
            'Data' => ["UserId" => "1"]
        ];
        $data=sendBack($data_send);

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

        $this->dispatch('mostrarToast', 'Obtener estudios', "Ha ocurrido un error al obtener los estudios");
        return false;
    }

    public function obtenerManagers($idestudio){
        if($idestudio!= "" && $idestudio!= 0){
            // //Genero la petición de informacion
            // $response = Http::withHeaders([
            //     'Authorization' => 'AAAA'
            // ])->withOptions([
            //     'verify' => false // Desactiva la verificación SSL
            // ])->post(config('app.API_URL'), [
            //     'Branch' => 'Server',
            //     'Service' => 'PlatformUser',
            //     'Action' => 'StudyInfo',
            //     'Data' => ["UserId" => "1"],
            //     "DataStudy"=> ["Id"=> $idestudio]
            // ]);

            // $data = $response->json();

            $data_send=[
                'Branch' => 'Server',
                'Service' => 'PlatformUser',
                'Action' => 'StudyInfo',
                'Data' => ["UserId" => "1"],
                "DataStudy"=> ["Id"=> $idestudio]
            ];
            $data=sendBack($data_send);

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

            $this->dispatch('mostrarToast', 'Obtener managers', "Ha ocurrido un error al obtener los managers");
            return false;
        }

    }

    public function updatedestudioactual($valor)
    {
        $this->listadomanagers=[];
        $this->manageractual=0;
        $this->obtenerManagers($this->estudioactual);
    }

    public function mount(){
        //Trato de obtener los estudios
        if($this->obtenerEstudios()){
            //Trato de obtener los managers
            if($this->obtenerManagers($this->estudioactual)){

            }
        }
    }

    public function render()
    {
        
        return view('livewire.modelos.create');
    }
}
