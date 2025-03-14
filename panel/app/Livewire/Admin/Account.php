<?php

namespace App\Livewire\Admin;

use App\Models\User;
use App\Models\Rank;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Account extends Component
{
    public $alerta=false;

    public $alerta_sucess="";
    public $alerta_error="";
    public $alerta_warning="";

    public $usuario;
    public $editing=false;

    //Los campos que me contienen la informacion
    public $username="";
    public $name="";
    public $email="";
    public $rank="0";

    public function activarEdicion(){
        $this->editing=true;

    }

    public function validar(){
        //Valido los campos
        if(!(preg_match('/^[a-zA-Z0-9._-]+$/', $this->username) && !empty(trim($this->username)))){
            $this->alerta=true;
            $this->alerta_warning="Alerta: El usuario no es válido";
            return;
        }
        elseif(!(preg_match('/^[a-zA-Z0-9\/\-\áéíóúÁÉÍÓÚüÜñÑ\s]+$/', $this->name) && !empty(trim($this->name)))){
            
            $this->alerta=true;
            $this->alerta_warning= "Alerta: El nombre no es válido";

            return;
        }
        elseif(!(preg_match('/^[\w\.-]+@[\w\.-]+\.\w{2,}$/', $this->email) && !empty(trim($this->email)))){
            $this->alerta=true;
            $this->alerta_warning= "Alerta: El email no es válido";

            return;
        }
        //Valido el rango
        elseif($this->rank>Rank::max('id') || $this->rank<1 || $this->rank>Auth::user()->rank){
            $this->alerta=true;
            $this->alerta_warning= "Alerta: El rango no es válido: ".$this->rank;

            return;
        }
        //Valido si el usuario ya existe
        elseif(User::where('username', $this->username)->exists()){
            $this->alerta=true;
            $this->alerta_warning="Este usuario ya está registrado";
            return;
        }

        return true;
    }

    public function modificar()
    {
        if($this->validar()){
            //asigno los valores
            $this->usuario->username = strtolower(trim($this->username));
            $this->usuario->name = $this->name;
            $this->usuario->email = $this->email;
            $this->usuario->rank = $this->rank;

            //Intento salvar
            if($this->usuario->save()){
                $this->alerta=true;
                $this->alerta_sucess="El usuario ha sido modificado correctamente";
                $this->editing=false;
                return;
            }else{
                $this->alerta=true;
                $this->alerta_error="Error modificando al usuario";
            }
        }
    }

    public function mount($usuario){
        //Almaceno el usuario
        $this->usuario = $usuario;

        //Cargo sus valores
        $this->username = $usuario->username;
        $this->name = $usuario->name;
        $this->email = $usuario->email;
        $this->rank = $usuario->rank;
    }
    public function render()
    {
        $rangos=Rank::where("id","<",Auth::user()->rank)->get();

        return view('livewire.admin.account',compact('rangos'));
    }
}
