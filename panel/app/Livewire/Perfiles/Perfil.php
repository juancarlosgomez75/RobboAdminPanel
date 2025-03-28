<?php

namespace App\Livewire\Perfiles;

use App\Models\User;
use Livewire\Component;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class Perfil extends Component
{

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

            $this->dispatch('mostrarToast', 'Editar perfil', "El nombre no es válido");

            return;
        }
        elseif(!(preg_match('/^[\w\.-]+@[\w\.-]+\.\w{2,}$/', $this->email) && !empty(trim($this->email)))){
            $this->dispatch('mostrarToast', 'Editar perfil', "El email no es válido");

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
                $this->dispatch('mostrarToast', 'Editar perfil', "Se ha actualizado tu información");
                $this->editing=false;
    
                registrarLog("Administracion","Perfil","Actualizar","Ha actualizado su propia información",true);
                return;
            }else{
                $this->dispatch('mostrarToast', 'Editar perfil', "Error actualizando tu información");
            }


        }
    }

    public function modificarPassword(){
        $user = Auth::user();

        // Verificar si la contraseña actual es correcta
        if (!Hash::check($this->password, $user->password)) {
            $this->dispatch('mostrarToast', 'Cambiar contraseña', "Tu contraseña actual no coincide");
            return;
        }
        elseif($this->passwordnew!=$this->passwordnewagain){
            $this->dispatch('mostrarToast', 'Cambiar contraseña', "Las contraseñas no coinciden");
            return;
        }
    
        // Verificar que la nueva contraseña sea diferente a la actual
        elseif (Hash::check($this->passwordnew, $user->password)) {
            $this->dispatch('mostrarToast', 'Cambiar contraseña', "No puedes asignar la misma contraseña");
            return;
        }
    
        // Actualizar la contraseña
        $usuario=User::find(Auth::user()->id);
        $usuario->password = Hash::make($this->passwordnew);
        $usuario->save();
    
        $this->dispatch('mostrarToast', 'Cambiar contraseña', "Se ha actualizado tu contraseña, vuelve a ingresar");

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
