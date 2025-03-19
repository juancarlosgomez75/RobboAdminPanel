<?php

namespace App\Livewire\Inventario;

use Livewire\Component;
use Illuminate\Support\Facades\Http;

class Order extends Component
{
    //Información de estudios
    public $estudios=[];
    public $study_search = "";
    public $studyFind=false;
    public $studyId="";
    public $studyInfo=[];
    public $study_name="";

    //Información de campos de información básica
    public $toStudy="-1";
    public $address="";
    public $city= "";
    public $receiver="";
    public $phone="";

    //Campos activos
    public $activeBasic=false;

    //El elemento de updated
    public function updatedtoStudy($valor)
    {
        $this->address="";
        $this->city= "";
        $this->receiver="";
        $this->phone="";
        $this->studyInfo=[];
        $this->studyId="";
        $this->studyFind=false;

        //Apago los campos si es el caso
        if($this->toStudy=="0"){
            $this->activeBasic=true;
        }else{
            $this->activeBasic=false;
        }


    }

    public function mount(){
        //Genero la petición para obtener la información
        $information = Http::withHeaders([
            'Authorization' => 'AAAA'
        ])->withOptions([
            'verify' => false // Desactiva la verificación SSL
        ])->post(config('app.API_URL'), [
            'Branch' => 'Server',
            'Service' => "GeneralParams",
            'Action' => "GeneralParams",
            'Data' => ["UserId" => "1"]
        ]);

        $generalinformation=$information->json();
        
        //Genero la petición de informacion
        $response = Http::withHeaders([
            'Authorization' => 'AAAA'
        ])->withOptions([
            'verify' => false // Desactiva la verificación SSL
        ])->post(config('app.API_URL'), [
            'Branch' => 'Server',
            'Service' => 'PlatformUser',
            'Action' => 'StudyList',
            'Data' => ["UserId" => "1"]
        ]);

        $data = $response->json();

        //Analizo si es válido lo que necesito
        if (isset($data['Status']) && isset($generalinformation['Status'])) {
            //Analizo si el status es true
            if($data["Status"] && $generalinformation["Status"]){

                // Mapear ciudades con su país
                $cityMap = [];
                if (isset($generalinformation['CountryList'])) {
                    foreach ($generalinformation['CountryList'] as $country) {
                        foreach ($country['Cities'] as $city) {
                            $cityMap[$city['Id']] = $city['CityName'] . ', ' . $country['CountryName'];
                        }
                    }
                }

                // Agregar el campo City en ListStudyData
                if (isset($data['ListStudyData'])) {
                    foreach ($data['ListStudyData'] as &$study) {
                        $study['City'] = $cityMap[$study['CityId']] ?? 'Desconocido';
                    }
                }
                $this->estudios=$data["ListStudyData"];
            } 
        }
    }

    public function buscarEstudio(){
        if(!(preg_match('/^[a-zA-Z0-9\/\-\áéíóúÁÉÍÓÚüÜñÑ\s]+$/', $this->study_search) && !empty(trim($this->study_search)))){
            
            $this->dispatch('mostrarToast', 'Buscar estudio', 'El campo no es válido');

            return;
        }

        //Recorro los estudios
        foreach($this->estudios as $estudio){
            //Analizo si el nombre coincide
            if(preg_match('/' . preg_quote(strtoupper($this->study_search), '/') . '/i', strtoupper($estudio["StudyName"]))){
                //Indico que localicé el estudio
                $this->studyFind=true;

                //Cargo toda la información
                $this->studyId=$estudio["Id"];
                $this->address=$estudio["Address"];
                $this->city= $estudio["City"];
                $this->receiver=$estudio["Contact"];
                $this->phone=$estudio["Phone"];
                $this->study_name=$estudio["StudyName"];

                //Almaceno toda la información en un campo, para impedir la edición
                $this->studyInfo=[
                    "Name"=>$this->study_name,
                    "Address"=>$this->address,
                    "City"=>$this->city,
                    "Contact"=>$this->receiver,
                    "Phone"=>$this->phone
                ];

                $this->activeBasic=true;

                //Notifico que lo encontré
                $this->dispatch('mostrarToast', 'Buscar estudio', 'Se ha cargado la información del estudio');

                //Cierro ese método
                return;
            }
        }

        $this->dispatch('mostrarToast', 'Buscar estudio', 'No se localizó el estudio');
        $this->activeBasic=false;
        $this->studyId="";
        $this->studyFind=false;
    }

    public function render()
    {
        return view('livewire.inventario.order');
    }
}
