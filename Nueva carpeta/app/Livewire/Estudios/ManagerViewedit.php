<?php

namespace App\Livewire\Estudios;

use Livewire\Component;

class ManagerViewedit extends Component
{
    public $editing=false;
    public $alerta=false;

    public $alerta_sucess="";
    public $alerta_error="";
    public $alerta_warning="";

    public $nombre="";
    public $telefono= "";
    public $email="";

    
    public function verificarCampos(){
        if(!(preg_match('/^[a-zA-Z0-9\/\-\áéíóúÁÉÍÓÚüÜñÑ\s]+$/', $this->nombre) && !empty(trim($this->nombre)))){
            
            $this->alerta=true;
            $this->alerta_warning= "Alerta: El nombre del manager no es válido";

            return false;
        }
        elseif(!(preg_match('/^\+?\d{1,3}?\(?\d{2,4}\)?\d{6,10}$/', $this->telefono) && !empty(trim($this->telefono)))){
            $this->alerta=true;
            $this->alerta_warning= "Alerta: El número de contacto no es válido";

            return false;
        }
        elseif(!(preg_match('/^[\w\.-]+@[\w\.-]+\.\w{2,}$/', $this->email) && !empty(trim($this->email)))){
            $this->alerta=true;
            $this->alerta_warning= "Alerta: El email no es válido";

            return false;
        }
        return true;
    }

    public function guardarEdicion(){
        if($this->verificarCampos()){
            $this->alerta=true;
            $this->alerta_sucess= "Se han modificado los datos correctamente";
            $this->editing=false;
        }
    }

    public function activarEdicion(){
        $this->editing=true;

    }

    public function render()
    {
        return view('livewire.estudios.manager-viewedit');
    }
}
