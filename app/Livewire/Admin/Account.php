<?php

namespace App\Livewire\Admin;

use App\Models\User;
use App\Models\Rank;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class Account extends Component
{

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
            $this->dispatch('mostrarToast', 'Modificar usuario', "Alerta: El usuario no es válido");
            return;
        }
        elseif(!(preg_match('/^[a-zA-Z0-9\/\-\áéíóúÁÉÍÓÚüÜñÑ\s]+$/', $this->name) && !empty(trim($this->name)))){
            
            $this->dispatch('mostrarToast', 'Modificar usuario', "Alerta: El nombre no es válido");

            return;
        }
        elseif(!(preg_match('/^[\w\.-]+@[\w\.-]+\.\w{2,}$/', $this->email) && !empty(trim($this->email)))){
            $this->dispatch('mostrarToast', 'Modificar usuario', "Alerta: El email no es válido");

            return;
        }
        //Valido el rango
        elseif($this->rank>Rank::max('id') || $this->rank<1 || $this->rank>Auth::user()->rank){
            $this->dispatch('mostrarToast', 'Modificar usuario', "Alerta: El rango no es válido");

            return;
        }
        //Valido si el usuario ya existe si es diferente
        elseif((User::where('username', $this->username)->exists()) && ($this->usuario->username!=$this->username)){
            $this->dispatch('mostrarToast', 'Modificar usuario', "Alerta: Este nombre de usuario ya existe");
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
                $this->dispatch('mostrarToast', 'Modificar usuario', "Usuario modificado correctamente");
                $this->editing=false;

                //Genero el log
                registrarLog("Administracion","Cuenta","Editar","Se ha editado al usuario: ".$this->usuario->name." (".$this->usuario->id."), detalles: ".$this->usuario->toJson());

                return;
            }else{
                $this->dispatch('mostrarToast', 'Modificar usuario', "Error al modificar al usuario, contacte a soporte");

                registrarLog("Administracion","Cuenta","Modificar","Ha intentado modificar al usuario: ".$this->usuario->name." (".$this->usuario->id.")");
            }
        }
    }

    public function desactivarUsuario()
    {
        $this->usuario->activo = false;

        //Intento salvar
        if($this->usuario->save()){
            $this->dispatch('mostrarToast', 'Desactivar usuario', "Se ha desactivado al usuario correctamente");

            //Genero el log
            registrarLog("Administracion","Cuenta","Desactivar","Se ha desactivado al usuario: ".$this->usuario->name." (".$this->usuario->id.")",true);

            return;
        }else{
            $this->dispatch('mostrarToast', 'Desactivar usuario', "Error al desactivar al usuario, contacte a soporte");
            registrarLog("Administracion","Cuenta","Desactivar","Ha intentado desactivar al usuario: ".$this->usuario->name." (".$this->usuario->id.")",false);
        }
    }

    public function activarUsuario()
    {
        $this->usuario->activo = true;

        //Intento salvar
        if($this->usuario->save()){
            $this->dispatch('mostrarToast', 'Activar usuario', "Se ha activado al usuario correctamente");


            //Genero el log
            registrarLog("Administracion","Cuenta","Activar","Se ha activado al usuario: ".$this->usuario->name." (".$this->usuario->id.")",true);

            return;
        }else{
            $this->dispatch('mostrarToast', 'activar usuario', "Error al activar al usuario, contacte a soporte");
            registrarLog("Administracion","Cuenta","Desactivar","Ha intentado activar al usuario: ".$this->usuario->name." (".$this->usuario->id.")",false);
        }
    }

    public function reiniciarPassword()
    {
        $this->usuario->password = Hash::make('123');

        //Intento salvar
        if($this->usuario->save()){
            $this->dispatch('mostrarToast', 'Reiniciar contraseña', "Se ha reiniciado la contraseña correctamente");
            // Cierra sesión en todos los dispositivos
            DB::table('sessions')->where('user_id', $this->usuario->id)->delete();

            registrarLog("Administracion","Cuenta","Reiniciar contraseña","Se ha reiniciado la contraseña al usuario: ".$this->usuario->name." (".$this->usuario->id.")",true);
            return;
        }else{
            $this->dispatch('mostrarToast', 'Reiniciar contraseña', "Error al reiniciar la contraseña, contacte a soporte");

            registrarLog("Administracion","Cuenta","Reiniciar contraseña","Se ha intentado reiniciar la contraseña al usuario: ".$this->usuario->name." (".$this->usuario->id.")",false);


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
