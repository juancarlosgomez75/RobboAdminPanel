<?php

namespace App\Livewire\Estudios;

use Illuminate\Support\Facades\Http;
use Livewire\Component;

class Create extends Component
{
    //Variables de alerta

    public $ciudades;

    //Variables
    public $nombre="";
    public $razonsocial="";
    public $nit=0;
    public $idciudad=0;
    public $direccion="";
    public $responsable="";
    public $telcontacto="";
    public $telcontacto2="";
    public $email="";

    public function validar(){

        if(!(preg_match('/^[a-zA-Z0-9\/\-\áéíóúÁÉÍÓÚüÜñÑ\s]+$/', $this->nombre) && !empty(trim($this->nombre)))){
            
            $this->dispatch('mostrarToast', 'Crear estudio', "Alerta: El nombre no es válido");

            return false;
        }
        elseif (!(preg_match('/^[a-zA-ZáéíóúÁÉÍÓÚüÜñÑ\s&]+$/', $this->razonsocial) && !empty(trim($this->razonsocial)))) {
            $this->dispatch('mostrarToast', 'Crear estudio', "Alerta: La razón social no es válida");
            return false;
        }
        elseif(!(is_numeric($this->nit) && $this->nit > 0)){
            
            $this->dispatch('mostrarToast', 'Crear estudio', "Alerta: El NIT no es válido");

            return false;
        }
        elseif(!(is_numeric($this->idciudad) && $this->idciudad > 0)){
            
            $this->dispatch('mostrarToast', 'Crear estudio', "Alerta: La ciudad no es válida");

            return false;
        }
        elseif(!(preg_match('/^[a-zA-Z0-9#\-. áéíóúÁÉÍÓÚüÜñÑ]+$/', $this->direccion) && !empty(trim($this->direccion)))){
            
            $this->dispatch('mostrarToast', 'Crear estudio', "Alerta: El dirección no es válida");

            return false;
        }
        elseif(!(preg_match('/^[a-zA-ZÀ-ÿ0-9#\-.\s]+$/', $this->responsable) && !empty(trim($this->responsable)))){
            $this->dispatch('mostrarToast', 'Crear estudio', "Alerta: El nombre de responsable no es válido");

            return false;
        }
        elseif(!(preg_match('/^\+?\d{1,3}?\(?\d{2,4}\)?\d{6,10}$/', $this->telcontacto) && !empty(trim($this->telcontacto)))){
            $this->dispatch('mostrarToast', 'Crear estudio', "Alerta: El número de contacto principal no es válido");

            return false;
        }
        elseif (!(preg_match('/^\+?\d{1,3}?\(?\d{2,4}\)?\d{6,10}$/', $this->telcontacto) && 
        (empty(trim($this->telcontacto2)) || $this->telcontacto2 === "0" || preg_match('/^\+?\d{1,3}?\(?\d{2,4}\)?\d{6,10}$/', $this->telcontacto2)))) { 

            $this->dispatch('mostrarToast', 'Crear estudio', "Alerta: El número de contacto secundario no es válido");
            
            return false;
        }
        elseif (!empty(trim($this->email))) {
            $emails = explode(';', $this->email);
            $regex = '/^[\w\.-]+@[\w\.-]+\.\w{2,}$/';
            foreach ($emails as $email) {
                $email = trim($email);
                if (!preg_match($regex, $email)) {
                    $this->dispatch('mostrarToast', 'Crear manager', "Alerta: El email '$email' no es válido");
                    return false;
                }
            }
        }


        //Valido la ciudad
        $encontrado=false;
        //Ahora analizo si no está en las ciudades que tengo
        foreach($this->ciudades as $ciudad){
            if($ciudad["Id"]==$this->idciudad){
                $encontrado=true;
                break;
            }
        }

        if($encontrado){
            return true;
        }
        else{
            $this->dispatch('mostrarToast', 'Crear estudio', "Alerta: La ciudad ingresada no se reconoce");

            return false;
        }

        
    }

    public function registrar()
    {
        if($this->validar()){


            $data_send=[
                'Branch' => 'Server',
                'Service' => 'PlatformUser',
                'Action' => 'CreateUpdateStudy',
                'DataStudy' => [
                    "StudyName"=>$this->nombre,
                    "RazonSocial"=>$this->razonsocial,
                    "Nit"=>$this->nit,
                    "CityId"=>$this->idciudad,
                    "Address"=>$this->direccion,
                    "Contact"=>$this->responsable,
                    "Phone"=>$this->telcontacto,
                    "Phone2"=>$this->telcontacto2,
                    "Email"=>$this->email,
                    "Active"=>true,
                ],
                "Data"=>[
                    "UserId"=>"1"
                ]
            ];
            $data=sendBack($data_send);
    

            if (isset($data['Status'])) {
                if($data['Status']){
                    registrarLog("Producción","Estudios","Crear estudio","Se ha creado el estudio: ".$this->nombre,true);

                    $this->dispatch('mostrarToast', 'Crear estudio', "Se ha registrado el estudio correctamente");

                    //Ahora registro al primer manager con la información actual
                    $data_ssend=[
                        'Branch' => 'Server',
                        'Service' => 'PlatformUser',
                        'Action' => 'StudyList',
                        'Data' => ["UserId" => "1"]
                    ];
                    $datas=sendBack($data_ssend);

                    if (isset($datas['Status'])) {
                            //Analizo si el status es true
                            if($datas["Status"]){
                                //Recorro los estudios
                                //dd($datas["ListStudyData"]);
                                foreach($datas["ListStudyData"] as $estudio){
                                    if($estudio["StudyName"]==strtoupper($this->nombre)){
                                        //Genero entonces al manager
                                        $data_sendmanager=[
                                            'Branch' => 'Server',
                                            'Service' => 'PlatformUser',
                                            'Action' => 'CreateEditUser',
                                            "Data"=>[
                                                "UserId"=>"1",
                                                "UserData"=>[
                                                    "Name"=>$this->responsable,
                                                    "Phone"=> $this->telcontacto,
                                                    "Email"=> $this->email,
                                                    "RolID"=>"1",
                                                    "Activo"=>True
                                                ]
                                                ],
                                            "DataStudy"=>[
                                                    "Id"=>$estudio["Id"]
                                            ]
                                        ];
                                        $dataman=sendBack($data_sendmanager);

                                        if (isset($dataman['Status'])) {
                                            if($dataman['Status']){
                                                registrarLog("Producción","Managers","Crear Manager","Se ha creado el manager: ".$this->responsable.", del estudio #".$estudio["Id"].", durante la creación del estudio",true);

                                                $this->dispatch('mostrarToast', 'Crear manager', "Se ha registrado a este manager correctamente");
                                                

                                            }else{

                                                $this->dispatch('mostrarToast', 'Crear manager', "Ha ocurrido un error durante la operación: ".($dataman['Error']??"Error no reportado"));


                                                registrarLog("Producción","Managers","Crear Manager","Se ha intentado crear al manager: ".$this->responsable.", del estudio #".$estudio["Id"].", durante la creación del estudio",false);
                                            }
                                        }
                                        else{
                                            $this->dispatch('mostrarToast', 'Crear manager', "No se ha generado al manager");
                                        }
                                    }
                                }
                            }
                            else{
                                $this->dispatch('mostrarToast', 'Crear estudio', "No se ha recuperado la ID de los estudios con error: ".$datas['Error']);
                            }
                    }
                    else{
                        $this->dispatch('mostrarToast', 'Crear estudio', "No se ha recuperado la ID de los estudios");
                    }

                    $this->resetExcept('ciudades');
                    return;
                }else{
                    registrarLog("Producción","Estudios","Crear estudio","Se ha intentado crear el estudio: ".$this->nombre,false);

                    $this->dispatch('mostrarToast', 'Crear estudio', "Ha ocurrido un error durante la operación: ".($data['Error']??"Error no reportado"));
                    return;
                }
            }

            $this->dispatch('mostrarToast', 'Crear estudio', "Ha ocurrido un error durante la operación, contacte a soporte");



        }

    }

    public function mount($Ciudades)
    {
        $this->ciudades = $Ciudades;
    }

    public function render()
    {
        return view('livewire.estudios.create',["Ciudades"=>$this->ciudades]);
    }
}
