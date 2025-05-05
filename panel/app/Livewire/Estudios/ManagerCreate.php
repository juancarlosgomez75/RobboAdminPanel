<?php

namespace App\Livewire\Estudios;

use Livewire\Component;

use Illuminate\Support\Facades\Http;

class ManagerCreate extends Component
{

    public $idEstudio=0;

    public $nombre="";
    public $telefono= "";
    public $email="";

    public function verificarCampos(){
        if(!(preg_match('/^[a-zA-Z0-9\/\-\áéíóúÁÉÍÓÚüÜñÑ\s]+$/', $this->nombre) && !empty(trim($this->nombre)))){
            
            $this->dispatch('mostrarToast', 'Crear manager', "Alerta: El nombre no es válido");

            return false;
        }
        elseif(!(preg_match('/^\+?\d{1,3}?\(?\d{2,4}\)?\d{6,10}$/', $this->telefono) && !empty(trim($this->telefono)))){
            $this->dispatch('mostrarToast', 'Crear manager', "Alerta: El número de contacto no es válido");

            return false;
        }
        elseif (!empty(trim($this->email))) {
            $emails = explode(';', $this->email);
            $regex = '/^[\w\.-]+@[\w\.-]+\.\w{2,}$/';
            foreach ($emails as $email) {
                $email = trim($email);
                if (!preg_match($regex, $email)) {
                    $this->dispatch('mostrarToast', 'Crear manager', "Alerta: El email '$email' no es válido");
                    return false;
                }
            }
        }
        return true;
    }

    public function guardar(){
        if($this->verificarCampos()){

            // //Genero la petición de informacion
            // $response = Http::withHeaders([
            //     'Authorization' => 'AAAA'
            // ])->withOptions([
            //     'verify' => false // Desactiva la verificación SSL
            // ])->post(config('app.API_URL'), [
            //     'Branch' => 'Server',
            //     'Service' => 'PlatformUser',
            //     'Action' => 'CreateEditUser',
            //     "Data"=>[
            //         "UserId"=>"1",
            //         "UserData"=>[
            //             "Name"=>$this->nombre,
            //             "Phone"=> $this->telefono,
            //             "Email"=> $this->email,
            //             "RolID"=>"1"
            //         ]
            //         ],
            //     "DataStudy"=>[
            //             "Id"=>$this->idEstudio
            //     ]
            // ]);

            // $data = $response->json();

            $data_send=[
                'Branch' => 'Server',
                'Service' => 'PlatformUser',
                'Action' => 'CreateEditUser',
                "Data"=>[
                    "UserId"=>"1",
                    "UserData"=>[
                        "Name"=>$this->nombre,
                        "Phone"=> $this->telefono,
                        "Email"=> $this->email,
                        "RolID"=>"1",
                        "Activo"=>True
                    ]
                    ],
                "DataStudy"=>[
                        "Id"=>$this->idEstudio
                ]
            ];
            $data=sendBack($data_send);

            if (isset($data['Status'])) {
                if($data['Status']){
                    registrarLog("Producción","Managers","Crear Manager","Se ha creado el manager: ".$this->nombre.", del estudio #".$this->idEstudio,true);

                    $this->resetExcept(['idEstudio']);
                    $this->dispatch('mostrarToast', 'Crear manager', "Se ha registrado a este manager correctamente");
                    
                    return;

                }else{

                    $this->dispatch('mostrarToast', 'Crear manager', "Ha ocurrido un error durante la operación: ".($data['Error']??"Error no reportado"));


                    registrarLog("Producción","Managers","Crear Manager","Se ha intentado crear al manager: ".$this->nombre.", del estudio #".$this->idEstudio,false);
                    return;
                }
            }

            $this->dispatch('mostrarToast', 'Crear manager', "Ha ocurrido un error durante la operación, por favor contacte a soporte");


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
