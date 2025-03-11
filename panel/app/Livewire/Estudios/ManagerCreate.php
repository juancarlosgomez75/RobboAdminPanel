<?php

namespace App\Livewire\Estudios;

use Livewire\Component;

use Illuminate\Support\Facades\Http;

class ManagerCreate extends Component
{
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

    public function guardar(){
        if($this->verificarCampos()){

            //Genero la petición de informacion
            $response = Http::withHeaders([
                'Authorization' => 'AAAA'
            ])->withOptions([
                'verify' => false // Desactiva la verificación SSL
            ])->post(config('app.API_URL'), [
                'Branch' => 'Server',
                'Service' => 'PlatformUser',
                'Action' => 'CreateUpdateStudy',
                'DataStudy' => [
                    "StudyName"=>$this->nombre,
                    "RazonSocial"=>$this->razonsocial,
                    "Nit"=>$this->nit,
                    "CityId"=>$this->idciudad,
                    "Address"=>$this->direccion,
                    "Contact"=>$this->responsable,
                    "Phone"=>$this->telcontacto
                ],
                "Data"=>[
                    "UserId"=>"1"
                ]
            ]);

            $data = $response->json();


            $this->alerta=true;
            $this->alerta_sucess= "Se ha registrado a esta persona correctamente";
        }
    }

    public function render()
    {
        return view('livewire.estudios.manager-create');
    }
}
