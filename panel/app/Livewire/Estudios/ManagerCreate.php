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

    public $idEstudio=0;

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
                'Action' => 'CreateEditUser',
                "Data"=>[
                    "UserId"=>"1",
                    "UserData"=>[
                        "Name"=>$this->nombre,
                        "Phone"=> $this->telefono,
                        "Email"=> $this->email,
                        "RolID"=>"1"
                    ]
                    ],
                "DataStudy"=>[
                        "Id"=>$this->idEstudio
                ]
            ]);

            $data = $response->json();

            if (isset($data['Status'])) {
                if($data['Status']){
                    $this->resetExcept(['idEstudio']);
                    $this->alerta=true;
                    $this->alerta_sucess= "Se ha registrado a este manager correctamente";
                    
                    return;

                }else{
                    $this->alerta=true;
                    $this->alerta_error= "Ha ocurrido un error durante la operación: ".($data['Error']??"Error no reportado");
                    return;
                }
            }

            $this->alerta=true;
            $this->alerta_error= "Ha ocurrido un error, contacte a soporte";


        }
    }

    public function mount($IdEstudio){
        $this->idEstudio=$IdEstudio;
    }
    public function render()
    {
        return view('livewire.estudios.manager-create');
    }
}
