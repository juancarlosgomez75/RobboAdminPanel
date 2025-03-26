<?php

namespace App\Livewire\Maquinas;

use Livewire\Component;
use Livewire\Volt\Compilers\Mount;
use Illuminate\Support\Facades\Http;

class Create extends Component
{
    public $alerta=false;

    public $alerta_sucess="";
    public $alerta_error="";
    public $alerta_warning="";

    public $information;

    //Los campos
    public $hardwareid;
    public $tipoid=0;
    public $estudioid=0;

    public function mount($information){
        $this->information=$information;
    }

    public function validar(){

        //Se reinician las alertas
        $this->alerta_sucess="";
        $this->alerta_error="";
        $this->alerta_warning="";

        if(!(is_numeric($this->hardwareid) && $this->hardwareid > 0)){
            
            $this->alerta=true;
            $this->alerta_warning= "Alerta: La id del hardware no es válida";

            return false;
        }
        elseif(!(in_array($this->tipoid,["1","2","3"]))){
            $this->alerta=true;
            $this->alerta_warning= "Alerta: El tipo de dispositivo no es válido";

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
            $this->alerta=true;
            $this->alerta_warning= "Alerta: El estudio no se reconoce";

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
                $this->alerta=true;
                $this->alerta_sucess= "Se ha registrado la máquina";

                
                return;
            }

            $this->alerta=true;
            $this->alerta_error= "Ha ocurrido un error, contacte a soporte";



        }

    }


    public function render()
    {
        return view('livewire.maquinas.create',["information"=>$this->information]);
    }
}
