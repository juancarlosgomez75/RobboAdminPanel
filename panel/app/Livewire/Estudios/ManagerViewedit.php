<?php

namespace App\Livewire\Estudios;

use Livewire\Component;
use Illuminate\Support\Facades\Http;
class ManagerViewedit extends Component
{

    public $editing=false;
    public $nombre="";
    public $telefono= "";
    public $email="";

    public $Information;
    public $Models;
    public $Study;

    public $activo;

    
    public function verificarCampos(){
        
        if(!(preg_match('/^[a-zA-Z0-9\/\-\áéíóúÁÉÍÓÚüÜñÑ\s]+$/', $this->nombre) && !empty(trim($this->nombre)))){

            $this->dispatch('mostrarToast', 'Editar manager', "Alerta: El nombre del manager no es válido");

            return false;
        }
        elseif(!(preg_match('/^\+?\d{1,3}?\(?\d{2,4}\)?\d{6,10}$/', $this->telefono) && !empty(trim($this->telefono)))){
            $this->dispatch('mostrarToast', 'Editar manager', "Alerta: El número de contacto no es válido");

            return false;
        }
        elseif(!(preg_match('/^[\w\.-]+@[\w\.-]+\.\w{2,}$/', $this->email) && !empty(trim($this->email)))){
            $this->dispatch('mostrarToast', 'Editar estudio', "Alerta: El email no es válido");

            return false;
        }
        return true;
    }

    public function guardarEdicion(){
        if($this->verificarCampos()){

            $apidata=[
                'Branch' => 'Server',
                'Service' => 'PlatformUser',
                'Action' => 'CreateEditUser',
                "Data"=>[
                    "UserId"=>"1",
                    "UserData"=>[
                        "Id"=>$this->Information["Id"],
                        "Name"=>$this->nombre,
                        "Phone"=> $this->telefono,
                        "Email"=> $this->email,
                        "RolID"=>"1",
                        "Activo"=>$this->activo
                        ]
                    ],
                "DataStudy"=>[
                    "Id"=>$this->Study["Id"]
                ]
                ];

            // //Genero la modificación
            // $response = Http::withHeaders([
            //     'Authorization' => 'AAAA'
            // ])->withOptions([
            //     'verify' => false // Desactiva la verificación SSL
            // ])->post(config('app.API_URL'), $apidata);

            // $data = $response->json();

            $data=sendBack($apidata);

            if (isset($data['Status'])) {
                if($data['Status']){
                    $this->dispatch('mostrarToast', 'Editar manager', "Se ha actualizado correctamente la información");
                    $this->editing=false;

                    registrarLog("Producción","Managers","Editar Manager","Se ha modificado al manager #".$this->Information["Id"].", del estudio #".$this->Study["Id"].", detalles: ".json_encode($apidata),true);
                    
                    return;

                }else{

                    $this->dispatch('mostrarToast', 'Editar manager', "Ha ocurrido un error durante la operación: ".($data['Error']??"Error no reportado"));


                    registrarLog("Producción","Managers","Editar Manager","Se ha intentado modificar al manager #".$this->Information["Id"].", del estudio #".$this->Study["Id"].", detalles: ".json_encode($apidata),false);
                    return;

                    
                }
            }

            $this->dispatch('mostrarToast', 'Editar manager', "Ha ocurrido un error durante la operación, contacte a soporte");
        }
    }

    public function activarEdicion(){
        $this->editing=true;

    }

    public function activarUsuario(){

        // //Genero la modificación
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
        //             "Id"=>$this->Information["Id"],
        //             "Name"=>$this->nombre,
        //             "Phone"=> $this->telefono,
        //             "Email"=> $this->email,
        //             "RolID"=>"1",
        //             "Activo"=>true
        //             ]
        //         ],
        //     "DataStudy"=>[
        //         "Id"=>$this->Study["Id"]
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
                    "Id"=>$this->Information["Id"],
                    "Name"=>$this->nombre,
                    "Phone"=> $this->telefono,
                    "Email"=> $this->email,
                    "RolID"=>"1",
                    "Activo"=>true
                    ]
                ],
            "DataStudy"=>[
                "Id"=>$this->Study["Id"]
            ]
        ];
        $data=sendBack($data_send);

        if (isset($data['Status'])) {
            if($data['Status']){
                $this->dispatch('mostrarToast', 'Activar manager', "Se ha activado a este usuario correctamente");
                $this->activo=true;
                
                registrarLog("Producción","Managers","Activar Manager","Se ha activado al manager #".$this->Information["Id"].", del estudio #".$this->Study["Id"],true);

                return;

            }else{

                $this->dispatch('mostrarToast', 'Activar manager', "Ha ocurrido un error durante la operación: ".($data['Error']??"Error no reportado"));

                registrarLog("Producción","Managers","Activar Manager","Se ha intentado activar al manager #".$this->Information["Id"].", del estudio #".$this->Study["Id"],false);
                return;
            }
        }

        $this->dispatch('mostrarToast', 'Activar manager', "Ha courrido un error, contacte a soporte");
    }

    public function desactivarUsuario(){
        //Genero la modificación
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
        //             "Id"=>$this->Information["Id"],
        //             "Name"=>$this->nombre,
        //             "Phone"=> $this->telefono,
        //             "Email"=> $this->email,
        //             "RolID"=>"1",
        //             "Activo"=>false
        //             ]
        //         ],
        //     "DataStudy"=>[
        //         "Id"=>$this->Study["Id"]
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
                    "Id"=>$this->Information["Id"],
                    "Name"=>$this->nombre,
                    "Phone"=> $this->telefono,
                    "Email"=> $this->email,
                    "RolID"=>"1",
                    "Activo"=>false
                    ]
                ],
            "DataStudy"=>[
                "Id"=>$this->Study["Id"]
            ]
        ];
        $data=sendBack($data_send);

        if (isset($data['Status'])) {
            if($data['Status']){
                $this->dispatch('mostrarToast', 'Desctivar manager', "Se ha desactivado a este usuario correctamente");
                $this->activo=false;
                
                registrarLog("Producción","Managers","Desactivar Manager","Se ha desactivado al manager #".$this->Information["Id"].", del estudio #".$this->Study["Id"],true);

                return;

            }else{
                $this->dispatch('mostrarToast', 'Desactivar manager', "Ha ocurrido un error durante la operación: ".($data['Error']??"Error no reportado"));
                registrarLog("Producción","Managers","Desactivar Manager","Se ha intentado desactivar al manager #".$this->Information["Id"].", del estudio #".$this->Study["Id"],false);
                return;
            }
        }

        $this->dispatch('mostrarToast', 'Desactivar manager', "Ha courrido un error, contacte a soporte");
    }

    public function mount($Information,$Models,$Study){
        $this->Information=$Information;
        $this->Models=$Models;
        $this->Study=$Study;

        //Cargo la info
        $this->nombre=$Information["Name"];
        $this->telefono=$Information["Phone"];
        $this->email=$Information["Email"];
        $this->activo=$Information["Activo"];
    }

    public function render()
    {
        return view('livewire.estudios.manager-viewedit');
    }
}
