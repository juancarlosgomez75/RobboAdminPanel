<?php

namespace App\Livewire\Perfiles;

use App\Models\User;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class Perfil extends Component
{
    public $alerta=false;

    public $alerta_sucess="";
    public $alerta_error="";
    public $alerta_warning="";

    public $editing=false;

    //Mi información
    public $username="";
    public $name="";
    public $email="";
    public $rank="";

    public function activarEdicion(){
        $this->editing=true;

    }
    public function validar(){
        if(!(preg_match('/^[a-zA-Z0-9\/\-\áéíóúÁÉÍÓÚüÜñÑ\s]+$/', $this->name) && !empty(trim($this->name)))){
            
            $this->alerta=true;
            $this->alerta_warning= "Alerta: El nombre no es válido";

            return;
        }
        elseif(!(preg_match('/^[\w\.-]+@[\w\.-]+\.\w{2,}$/', $this->email) && !empty(trim($this->email)))){
            $this->alerta=true;
            $this->alerta_warning= "Alerta: El email no es válido";

            return;
        }

        return true;
    }
    public function modificar(){
        if($this->validar()){
            //Localizo el usuario
            $usuario=User::find(Auth::user()->id);

            //Lo modifico
            $usuario->name=$this->name;
            $usuario->email=$this->email;

            if($usuario->save()){
                $this->alerta=true;
                $this->alerta_sucess="Tu cuenta ha sido actualizada";
                $this->editing=false;
    
                registrarLog("Administracion","Perfil","Actualizar","Ha actualizado su propia información",true);
                return;
            }else{
                $this->alerta=true;
                $this->alerta_error="No se ha logrado actualizar tu cuenta";
            }


        }
    }

    public function mount(){
        $this->username=Auth::user()->username;
        $this->name=Auth::user()->name;
        $this->email=Auth::user()->email;
        $this->rank=Auth::user()->rank_info->name;
    }

    public function render()
    {
        return view('livewire.perfiles.perfil');
    }
}
