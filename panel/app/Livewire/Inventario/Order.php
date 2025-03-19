<?php

namespace App\Livewire\Inventario;

use App\Models\ProductOrder;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
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
    public $activeBasic=false;

    //Información de campos de información básica
    public $toStudy="-1";
    public $address="";
    public $city= "";
    public $receiver="";
    public $phone="";

    //Información de los productos
    public $listProducts=[];
    public $addingProduct=false;

    public $product_name="";
    public $product_amount=1;

    //Información de los detalles
    public $details="";

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
        $this->address="";
        $this->city= "";
        $this->receiver="";
        $this->phone="";        
        $this->studyFind=false;
    }

    public function startAdding(){
        $this->addingProduct=true;
    }

    public function cancelAdding(){
        $this->addingProduct=false;

        //Reinicio los campos
        $this->product_name="";
        $this->product_amount=1;
    }

    public function removeProduct($index)
    {
        unset($this->listProducts[$index]); // Elimina el elemento del array
        $this->listProducts = array_values($this->listProducts); // Reorganiza los índices
        $this->dispatch('mostrarToast', 'Quitar producto', 'Se ha quitado el producto del carrito');

    }

    public function addProduct(){
        if(!(preg_match('/^[a-zA-Z0-9\/\-\áéíóúÁÉÍÓÚüÜñÑ\s]+$/', $this->product_name) && !empty(trim($this->product_name)))){
            
            $this->dispatch('mostrarToast', 'Añadir producto', 'El campo no es válido');

            return;
        }

        //Busco el producto
        $busqueda=Product::where("name", "LIKE", "%".$this->product_name."%")
        ->where("available", "=", "1")
        ->first();

        if($busqueda){
            //Añado el producto
            $this->listProducts[]=[
                "id"=>$busqueda->id,
                "name"=>$busqueda->name,
                "amount"=>$this->product_amount
            ];

            //Reinicio los campos
            $this->product_name="";
            $this->product_amount=1;

            //Cierro el editando
            $this->addingProduct=false;

            //Notifico que se ha añadido
            $this->dispatch('mostrarToast', 'Añadir producto', 'Se ha añadido el producto al carrito');

        }else{
            $this->dispatch('mostrarToast', 'Añadir producto', 'No se ha localizado el producto');
        }
 
    }

    public function validar(){
        //Analizo los campos si no es un estudio
        if(!$this->studyFind){
            if(!(preg_match('/^[a-zA-Z0-9#\-. áéíóúÁÉÍÓÚüÜñÑ]+$/', $this->address) && !empty(trim($this->address)))){
                $this->dispatch('mostrarToast', 'Crear pedido', 'La dirección no es válida');
                return false;
            }
            elseif(!(preg_match('/^[a-zA-Z0-9\/\-\áéíóúÁÉÍÓÚüÜñÑ\s]+$/', $this->city) && !empty(trim($this->city)))){
                $this->dispatch('mostrarToast', 'Crear pedido', 'La ciudad no es válida');
                return false;
            }
            elseif(!(preg_match('/^[a-zA-ZÀ-ÿ0-9#\-.\s]+$/', $this->receiver) && !empty(trim($this->receiver)))){
                $this->dispatch('mostrarToast', 'Crear pedido', 'El nombre de quién recibe no es válido');
                return false;
            }
            elseif(!(preg_match('/^[\d+\-]+$/', $this->phone) && !empty(trim($this->phone)))){
                $this->dispatch('mostrarToast', 'Crear pedido', 'El teléfono de contacto no es válido');
                return false;
            }
        }
        //Si es un estudio, recargo toda la info por seguridad
        else{
            $this->address=$this->studyInfo['Address'];
            $this->city=$this->studyInfo['City'];
            $this->receiver=$this->studyInfo['Contact'];
            $this->phone=$this->studyInfo['Phone'];
        }

        //Ahora valido si tiene o no producto añadidos
        if(empty($this->listProducts)){
            $this->dispatch('mostrarToast', 'Crear pedido', 'El carrito de compras está vacío');
            return false;
        }

        //Ahora valido las observaciones
        if (!empty(trim($this->details)) && !preg_match('/^[a-zA-Z0-9\/\-\áéíóúÁÉÍÓÚüÜñÑ\s]+$/', $this->details)){
            $this->dispatch('mostrarToast', 'Crear pedido', 'Las observaciones no son válidas');
            return false;
        }

        return true;
    }

    public function crear(){

        if($this->validar()){
            //Creo el objeto
            $orden=new ProductOrder();

            //Cargo la información básica
            $orden->address=$this->address;
            $orden->city=$this->city;
            $orden->name=$this->receiver;
            $orden->phone=$this->phone;

            if($this->studyFind){
                $orden->study_id=$this->studyId;
            }

            //Genero los detalles de creación
            $orden->creator=Auth::id();
            $orden->creation_notes=$this->details;
            $orden->creation_list=json_encode($this->listProducts);

            //Intento guardar
            if($orden->save()){
                $this->dispatch('mostrarToast', 'Crear pedido', 'Se ha generado el pedido satisfactoriamente');
                $this->resetExcept("estudios");
            }else{
                $this->dispatch('mostrarToast', 'Crear pedido', 'Ha ocurrido un error al generar el pedido, contacte a soporte');
            }

            
        }

    }
    public function render()
    {
        return view('livewire.inventario.order');
    }
}
