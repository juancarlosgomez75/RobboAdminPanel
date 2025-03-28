<?php

namespace App\Livewire\Admin;

use App\Models\Rank;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;

class Accounts extends Component
{

    public $username="";
    public $name="";
    public $email="";
    public $rank="0";

    public function validar(){
        //Valido los campos
        if(!(preg_match('/^[a-zA-Z0-9._-]+$/', $this->username) && !empty(trim($this->username)))){
            $this->dispatch('mostrarToast', 'Crear usuario', "Alerta: El usuario no es válido");
            return;
        }
        elseif(!(preg_match('/^[a-zA-Z0-9\/\-\áéíóúÁÉÍÓÚüÜñÑ\s]+$/', $this->name) && !empty(trim($this->name)))){
            
            $this->dispatch('mostrarToast', 'Crear usuario', "Alerta: El nombre no es válido");

            return;
        }
        elseif(!(preg_match('/^[\w\.-]+@[\w\.-]+\.\w{2,}$/', $this->email) && !empty(trim($this->email)))){
            $this->dispatch('mostrarToast', 'Crear usuario', "Alerta: El email no es válido");

            return;
        }
        //Valido el rango
        elseif($this->rank>Rank::max('id') || $this->rank<1 || $this->rank>Auth::user()->rank){
            $this->dispatch('mostrarToast', 'Crear usuario', "Alerta: El rango no es válido");

            return;
        }
        //Valido si el usuario ya existe
        elseif(User::where('username', $this->username)->exists()){
            $this->dispatch('mostrarToast', 'Crear usuario', "Alerta: Este usuario ya existe");
            return;
        }

        return true;
    }

    public function registrar(){
        if($this->validar()){
            //Creo el objeto y asigno los valores
            $usuario = new User();
            $usuario->username = strtolower(trim($this->username));
            $usuario->name = $this->name;
            $usuario->email = $this->email;
            $usuario->password = Hash::make('123');
            $usuario->rank = $this->rank;

            //Intento salvar
            if($usuario->save()){
                $this->dispatch('mostrarToast', 'Crear usuario', "Se ha registrado al usuario correctamente");

                //Reinicio las variables
                $this->username="";
                $this->name="";
                $this->email="";
                $this->rank="0";

                registrarLog("Administracion","Cuentas","Crear","Se ha creado al usuario: ".$usuario->name." (".$usuario->id."), detalles: ".$usuario->toJson(),true);

                return;
            }else{
                $this->dispatch('mostrarToast', 'Crear usuario', "Error al registrar el usuario,contacte a soporte");

                registrarLog("Administracion","Cuentas","Crear","Se ha intentado crear al usuario: ".$usuario->name." (".$usuario->id."), detalles: ".$usuario->toJson(),false);
            }
        }
    }
    public function render()
    {

        $accounts = User::all();

        $rangos=Rank::where("id","<",Auth::user()->rank)->get();
        

        return view('livewire.admin.accounts',compact('accounts','rangos'));
    }
}
