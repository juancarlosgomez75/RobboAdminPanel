<?php

namespace App\Livewire\Estudios;

use Livewire\Component;

class Viewedit extends Component
{

    public $alerta=false;

    public $alerta_sucess="";
    public $alerta_error="";
    public $alerta_warning="";

    public $editing=false;

    public $estudioactual;
    public $managers;
    public $ciudades;

    public $ciudadactual;

    //Variables
    public $nombre="";
    public $razonsocial="";
    public $nit=0;
    public $idciudad=0;
    public $direccion="";
    public $responsable="";
    public $telcontacto="";

    public function validar(){
        if(!(preg_match('/^[a-zA-Z0-9\/\-\áéíóúÁÉÍÓÚüÜñÑ\s]+$/', $this->nombre) && !empty(trim($this->nombre)))){
            
            $this->alerta=true;
            $this->alerta_warning= "Alerta: El nombre del estudio no es válido";

            return false;
        }
        elseif(!(preg_match('/^[a-zA-ZáéíóúÁÉÍÓÚüÜñÑ\s]+$/', $this->razonsocial) && !empty(trim($this->razonsocial)))){
            $this->alerta=true;
            $this->alerta_warning= "Alerta: La razón social no es válida";

            return false;
        }
        elseif(!(is_numeric($this->nit) && $this->nit > 0)){
            
            $this->alerta=true;
            $this->alerta_warning= "Alerta: El NIT no es válido";

            return false;
        }
        elseif(!(is_numeric($this->idciudad) && $this->idciudad > 0)){
            
            $this->alerta=true;
            $this->alerta_warning= "Alerta: La ciudad no se reconoce";

            return false;
        }
        elseif(!(preg_match('/^[a-zA-Z0-9#\-. áéíóúÁÉÍÓÚüÜñÑ]+$/', $this->direccion) && !empty(trim($this->direccion)))){
            
            $this->alerta=true;
            $this->alerta_warning= "Alerta: La dirección no es válida: ".$this->direccion;

            return false;
        }
        elseif(!(preg_match('/^[a-zA-ZÀ-ÿ0-9#\-.\s]+$/', $this->responsable) && !empty(trim($this->responsable)))){
            $this->alerta=true;
            $this->alerta_warning= "Alerta: El nombre del responsable no es válido";

            return false;
        }
        elseif(!(preg_match('/^\+?\d{1,3}?\(?\d{2,4}\)?\d{6,10}$/', $this->telcontacto) && !empty(trim($this->telcontacto)))){
            $this->alerta=true;
            $this->alerta_warning= "Alerta: El número de contacto no es válido";

            return false;
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
            $this->alerta=true;
            $this->alerta_warning= "Alerta: La ciudad no se reconoce";

            return false;
        }

        
    }

    public function modificar()
    {
        if($this->validar()){

            // //Ahora envio el post al sistema
            // $API="https://robbocock.online:8443/WSIntegration-1.0/resources/restapi/transaction";
            
            // //Genero la petición de informacion
            // $response = Http::withHeaders([
            //     'Authorization' => 'AAAA'
            // ])->withOptions([
            //     'verify' => false // Desactiva la verificación SSL
            // ])->post($API, [
            //     'Branch' => 'Server',
            //     'Service' => 'PlatformUser',
            //     'Action' => 'CreateUpdateStudy',
            //     'DataStudy' => [
            //         "Id"=>null,
            //         "StudyName"=>$this->nombre,
            //         "RazonSocial"=>$this->razonsocial,
            //         "Nit"=>$this->nit,
            //         "CityId"=>$this->idciudad,
            //         "Address"=>$this->direccion,
            //         "Contact"=>$this->responsable,
            //         "Phone"=>$this->telcontacto
            //     ]
            // ]);

            // $data = $response->json();

            // if (isset($data['Status'])) {
            //     if($data['Status']){
            //         $this->resetExcept('ciudades');
            //         $this->alerta=true;
            //         $this->alerta_sucess= "Se ha registrado el estudio de forma satisfactoria";
            //         return;
            //     }
            // }

            $this->alerta=true;
            $this->alerta_error= "Ha ocurrido un error, contacte a soporte";



        }

    }

    public function mount($EstudioActual,$Managers,$Ciudades,$CiudadActual){
        $this->estudioactual=$EstudioActual;
        $this->managers = $Managers;
        $this->ciudades = $Ciudades;
        $this->ciudadactual = $CiudadActual;
    }

    public function activarEdicion(){
        $this->editing=true;

    }
    public function render()
    {
        return view('livewire.estudios.viewedit',["estudioactual"=>$this->estudioactual, "managers"=> $this->managers,"Ciudades"=> $this->ciudades,"CiudadActual"=>$this->ciudadactual]);
    }
}
