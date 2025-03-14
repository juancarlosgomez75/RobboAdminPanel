<?php

namespace App\Livewire\Estudios;

use Livewire\Component;
use Illuminate\Support\Facades\Http;
class ManagerViewedit extends Component
{
    public $editing=false;
    public $alerta=false;

    public $alerta_sucess="";
    public $alerta_error="";
    public $alerta_warning="";

    public $nombre="";
    public $telefono= "";
    public $email="";

    public $Information;
    public $Models;
    public $Study;

    public $activo;

    
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
                        "RolID"=>"1"
                        ]
                    ],
                "DataStudy"=>[
                    "Id"=>$this->Study["Id"]
                ]
                ];

            //Genero la modificación
            $response = Http::withHeaders([
                'Authorization' => 'AAAA'
            ])->withOptions([
                'verify' => false // Desactiva la verificación SSL
            ])->post(config('app.API_URL'), $apidata);

            $data = $response->json();

            if (isset($data['Status'])) {
                if($data['Status']){
                    $this->alerta=true;
                    $this->alerta_sucess= "Se ha actualizado correctamente la información";
                    $this->editing=false;

                    registrarLog("Producción","Managers","Editar Manager","Se ha modificado al manager #".$this->Information["Id"].", del estudio #".$this->Study["Id"].", detalles: ".json_encode($apidata),true);
                    
                    return;

                }else{
                    $this->alerta=true;
                    $this->alerta_error= "Ha ocurrido un error durante la operación: ".($data['Error']??"Error no reportado");
                    registrarLog("Producción","Managers","Editar Manager","Se ha intentado modificar al manager #".$this->Information["Id"].", del estudio #".$this->Study["Id"].", detalles: ".json_encode($apidata),false);
                    return;

                    
                }
            }

            $this->alerta=true;
            $this->alerta_error= "Ha ocurrido un error, contacte a soporte";
        }
    }

    public function activarEdicion(){
        $this->editing=true;

    }

    public function activarUsuario(){

        //Genero la modificación
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
        ]);

        $data = $response->json();

        if (isset($data['Status'])) {
            if($data['Status']){
                $this->alerta=true;
                $this->alerta_sucess= "Se ha activado esta cuenta satisfactoriamente";
                $this->activo=true;
                
                registrarLog("Producción","Managers","Activar Manager","Se ha activado al manager #".$this->Information["Id"].", del estudio #".$this->Study["Id"],true);

                return;

            }else{
                $this->alerta=true;
                $this->alerta_error= "Ha ocurrido un error durante la operación: ".($data['Error']??"Error no reportado");
                registrarLog("Producción","Managers","Activar Manager","Se ha intentado activar al manager #".$this->Information["Id"].", del estudio #".$this->Study["Id"],false);
                return;
            }
        }

        $this->alerta=true;
        $this->alerta_error= "Ha ocurrido un error, contacte a soporte";
    }

    public function desactivarUsuario(){
        //Genero la modificación
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
        ]);

        $data = $response->json();

        if (isset($data['Status'])) {
            if($data['Status']){
                $this->alerta=true;
                $this->alerta_sucess= "Se ha desactivado esta cuenta satisfactoriamente";
                $this->activo=false;
                
                registrarLog("Producción","Managers","Desactivar Manager","Se ha desactivado al manager #".$this->Information["Id"].", del estudio #".$this->Study["Id"],true);

                return;

            }else{
                $this->alerta=true;
                $this->alerta_error= "Ha ocurrido un error durante la operación: ".($data['Error']??"Error no reportado");
                registrarLog("Producción","Managers","Desactivar Manager","Se ha intentado desactivar al manager #".$this->Information["Id"].", del estudio #".$this->Study["Id"],false);
                return;
            }
        }

        $this->alerta=true;
        $this->alerta_error= "Ha ocurrido un error, contacte a soporte";
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
