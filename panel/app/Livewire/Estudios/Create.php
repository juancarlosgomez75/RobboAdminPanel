<?php

namespace App\Livewire\Estudios;

use Livewire\Component;

class Create extends Component
{
    //Variables de alerta
    public $alerta=false;

    public $alerta_sucess="";
    public $alerta_error="";
    public $alerta_warning="";

    public $ciudades;

    //Variables
    public $nombre="";
    public $razonsocial="";
    public $nit=0;
    public $idciudad=0;
    public $direccion="";
    public $responsable="";
    public $telcontacto="";

    public function validar(){
        if(!(preg_match('/^[a-zA-Z0-9\/\-ZáéíóúÁÉÍÓÚüÜñÑ\s]+$/', $this->nombre) && !empty(trim($this->nombre)))){
            
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

    public function registrar()
    {
        if($this->validar()){
            $this->alerta=true;
            $this->alerta_sucess= "Sii".$this->telcontacto;
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
