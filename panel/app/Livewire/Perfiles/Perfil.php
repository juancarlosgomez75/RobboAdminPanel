<?php

namespace App\Livewire\Perfiles;

use App\Models\User;
use Livewire\Component;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

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
    public $password;
    public $passwordnew;
    public $passwordnewagain;

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

    public function modificarPassword(){
        $user = Auth::user();

        // Verificar si la contraseña actual es correcta
        if (!Hash::check($this->password, $user->password)) {
            $this->alerta=true;
            $this->alerta_error="Tu contraseña actual no coincide";
            return;
        }
        elseif($this->passwordnew!=$this->passwordnewagain){
            $this->alerta=true;
            $this->alerta_error="Las contraseñas nuevas no coinciden";
            return;
        }
    
        // Verificar que la nueva contraseña sea diferente a la actual
        elseif (Hash::check($this->passwordnew, $user->password)) {
            $this->alerta=true;
            $this->alerta_error="No puedes asignar la misma contraseña";
            return;
        }
    
        // Actualizar la contraseña
        $usuario=User::find(Auth::user()->id);
        $usuario->password = Hash::make($this->passwordnew);
        $usuario->save();
    
        $this->alerta=true;
        $this->alerta_sucess="Se ha cambiado tu contraseña, por favor inicia sesión nuevamente";

        Auth::logout();

        session()->invalidate();
        session()->regenerateToken();

        redirect('/logout');
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
