<?php

namespace App\Livewire\Maquinas;

use Livewire\Component;
use Livewire\Volt\Compilers\Mount;
use Illuminate\Support\Facades\Http;

class Create extends Component
{

    public $information;

    //Los campos
    public $hardwareid;
    public $tipoid=0;
    public $estudioid=0;

    public function mount($information){
        $this->information=$information;
    }

    public function validar(){

        if(!(is_numeric($this->hardwareid) && $this->hardwareid > 0)){

            $this->dispatch('mostrarToast', 'Crear máquina', "Alerta: La id del hardware no es válida");

            return false;
        }
        elseif(!(in_array($this->tipoid,["1","2","3"]))){
            $this->dispatch('mostrarToast', 'Crear máquina', "Alerta: El tipo de dispositivo no es válido");

            return false;
        }

        //Valido el estudio
        $encontrado=false;
        //Ahora analizo si no está en las ciudades que tengo
        foreach($this->information as $estudio){
            if($estudio["Id"]==$this->estudioid){
                $encontrado=true;
                break;
            }
        }

        if($encontrado){

            return true;
        }
        else{
            $this->dispatch('mostrarToast', 'Crear máquina', "Alerta: El estudio no se reconoce");

            return false;
        }

        
    }

    public function guardar()
    {
        if($this->validar()){

            
            // //Genero la petición de informacion
            // $response = Http::withHeaders([
            //     'Authorization' => 'AAAA'
            // ])->withOptions([
            //     'verify' => false // Desactiva la verificación SSL
            // ])->post(config('app.API_URL'), [
            //     'Branch' => 'Server',
            //     'Service' => 'PlatformUser',
            //     'Action' => 'CreateUpdateStudy',
            //     'DataStudy' => [
            //         "StudyName"=>$this->nombre,
            //         "RazonSocial"=>$this->razonsocial,
            //         "Nit"=>$this->nit,
            //         "CityId"=>$this->idciudad,
            //         "Address"=>$this->direccion,
            //         "Contact"=>$this->responsable,
            //         "Phone"=>$this->telcontacto
            //     ],
            //     "Data"=>[
            //         "UserId"=>"1"
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
            if(true){

                $this->resetExcept('information'); 
                $this->dispatch('mostrarToast', 'Crear máquina', "Se ha registrado la máquina correctamente");

                
                return;
            }

            $this->dispatch('mostrarToast', 'Crear máquina', "Ha ocurrido un error, contacte a soporte");



        }

    }


    public function render()
    {
        return view('livewire.maquinas.create',["information"=>$this->information]);
    }
}
