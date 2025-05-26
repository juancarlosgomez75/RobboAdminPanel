<?php

namespace App\Livewire\Inventario;

use App\Models\ProductOrder;
use App\Models\Product;
use App\Models\ProductInventoryMovement;
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
    public $tipoOrden="0";

    //Información de los productos
    public $listProducts=[];
    public $addingProduct=false;

    public $product_name="";
    public $product_amount=1;

    //Información de los detalles
    public $details="";

    public $searchResults;
    public $finded=false;

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
        // //Genero la petición para obtener la información
        // $information = Http::withHeaders([
        //     'Authorization' => 'AAAA'
        // ])->withOptions([
        //     'verify' => false // Desactiva la verificación SSL
        // ])->post(config('app.API_URL'), [
        //     'Branch' => 'Server',
        //     'Service' => "GeneralParams",
        //     'Action' => "GeneralParams",
        //     'Data' => ["UserId" => "1"]
        // ]);

        // $generalinformation=$information->json();

        $data_send=[
            'Branch' => 'Server',
            'Service' => "GeneralParams",
            'Action' => "GeneralParams",
            'Data' => ["UserId" => "1"]
        ];
        $generalinformation=sendBack($data_send);
        
        // //Genero la petición de informacion
        // $response = Http::withHeaders([
        //     'Authorization' => 'AAAA'
        // ])->withOptions([
        //     'verify' => false // Desactiva la verificación SSL
        // ])->post(config('app.API_URL'), [
        //     'Branch' => 'Server',
        //     'Service' => 'PlatformUser',
        //     'Action' => 'StudyList',
        //     'Data' => ["UserId" => "1"]
        // ]);

        // $data = $response->json();

        $data_send=[
            'Branch' => 'Server',
            'Service' => 'PlatformUser',
            'Action' => 'StudyList',
            'Data' => ["UserId" => "1"]
        ];
        $data=sendBack($data_send);

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
                
                if(!$estudio["Active"]){
                    $this->dispatch('mostrarToast', 'Buscar estudio', 'Se ha localizado al estudio, pero no se puede usar porque está inhabilitado');
                    return;
                }
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

    public function cancelSearch(){
        $this->addingProduct=false;
        $this->finded=false;
        $this->searchResults=[];

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

    public function searchProduct(){
        if(!(preg_match('/^[a-zA-Z0-9\/\-\áéíóúÁÉÍÓÚüÜñÑ\s]+$/', $this->product_name) && !empty(trim($this->product_name)))){
            
            $this->dispatch('mostrarToast', 'Buscar producto', 'El campo no es válido');

            return;
        }

        $searchTerm = preg_replace('/\s+/', '%', trim($this->product_name)); // Reemplaza espacios múltiples con %

        $this->searchResults = Product::whereRaw("LOWER(name) LIKE LOWER(?)", ["%" . $searchTerm . "%"])
            ->where("available", "=", "1")
            ->get();

        //Indico que debe mostrar resultados
        $this->finded=true;

    }
    public function addProduct($index){

        if(!isset($this->searchResults[$index])){
            $this->dispatch('mostrarToast', 'Añadir producto', 'No se ha localizado el producto');
            return;
        }

        $busqueda=$this->searchResults[$index];

        //Busco si ya existe
        foreach($this->listProducts as $product){
            if($product["id"]==$busqueda->id){

                $this->dispatch('mostrarToast', 'Añadir producto', 'Advertencia: Este producto ya está en el carrito');
                return;
            }
        }

        //Añado el producto
        $this->listProducts[]=[
            "id"=>$busqueda->id,
            "name"=>$busqueda->name,
            "amount"=>1,
            "use_firmware"=> $busqueda->use_firmwareid,
        ];

        $this->dispatch('mostrarToast', 'Añadir producto', 'Producto añadido');

    }

    public function validar(){
        //Analizo los campos si no es un estudio
        if(!$this->studyFind){
            if(!(preg_match('/^[a-zA-Z0-9#\-. áéíóúÁÉÍÓÚüÜñÑ]+$/', $this->address) && !empty(trim($this->address)))){
                $this->dispatch('mostrarToast', 'Crear orden', 'La dirección no es válida');
                return false;
            }
            elseif(!(preg_match('/^[a-zA-Z0-9\/\-\áéíóúÁÉÍÓÚüÜñÑ\s]+$/', $this->city) && !empty(trim($this->city)))){
                $this->dispatch('mostrarToast', 'Crear orden', 'La ciudad no es válida');
                return false;
            }
            elseif(!(preg_match('/^[a-zA-ZÀ-ÿ0-9#\-.\s]+$/', $this->receiver) && !empty(trim($this->receiver)))){
                $this->dispatch('mostrarToast', 'Crear orden', 'El nombre de quién recibe no es válido');
                return false;
            }
            elseif(!(preg_match('/^[\d+\-]+$/', $this->phone) && !empty(trim($this->phone)))){
                $this->dispatch('mostrarToast', 'Crear orden', 'El teléfono de contacto no es válido');
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
            $this->dispatch('mostrarToast', 'Crear orden', 'El carrito de compras está vacío');
            return false;
        }

        //Valido el tipo
        if($this->tipoOrden!="-1" && $this->tipoOrden!="1"){
            $this->dispatch('mostrarToast', 'Crear orden', 'El tipo de orden no es válido');
            return false;
        }

        //Ahora valido las observaciones
        if (!empty(trim($this->details)) && preg_match('/<[^>]*>/', $this->details)){
            $this->dispatch('mostrarToast', 'Crear orden', 'Las observaciones no son válidas');
            return false;
        }

        //Valido las cantidades
        foreach($this->listProducts as $product){

            //Busco el producto
            $buscado=Product::find($product['id']);

            if($buscado){
                if($buscado->inventory->stock_available<$product['amount'] && $this->tipoOrden=="-1"){
                    $this->dispatch('mostrarToast', 'Crear orden', 'El producto: '.$product["name"].' no tiene stock suficiente para completar la orden');
                    return false;
                }
                elseif($product['amount']<=0){
                    $this->dispatch('mostrarToast', 'Crear orden', 'La cantidad del producto: '.$product["name"].' no es válida');
                    return false;
                }
            }else{
                $this->dispatch('mostrarToast', 'Crear orden', 'El producto: '.$product["name"].' no fue encontrado');
                return false;
            }
        }

        return true;
    }

    public function crear(){

        if($this->validar()){
            //Creo el objeto
            $orden=new ProductOrder();

            //Cargo la información básica
            $orden->address=mb_strtoupper($this->address, 'UTF-8');
            $orden->city=mb_strtoupper($this->city, 'UTF-8');
            $orden->name=mb_strtoupper($this->receiver, 'UTF-8');
            $orden->phone=$this->phone;

            //Cargo el tipo
            $orden->type=($this->tipoOrden == -1) ? 'shipping' : 'collection';

            if($this->studyFind){
                $orden->study_id=$this->studyId;
            }

            //Genero los detalles de creación
            $orden->created_by=Auth::id();
            $orden->creation_notes=strip_tags($this->details);
            $orden->creation_list=json_encode($this->listProducts);

            //Intento guardar
            if($orden->save()){

                registrarLog("Inventario","Órdenes","Crear","Ha creado una orden con la siguiente información: ".json_encode($orden),true);

                //Ahora descargo los productossi es de tipo shipping
                if($orden->type=="shipping"){
                    foreach($this->listProducts as $element){

                        //Busco el producto
                        $pto=Product::find($element['id']);

                        //Genero un nuevo movimiento
                        $mov=new ProductInventoryMovement();

                        //Almaceno la información
                        $mov->inventory_id=$pto->inventory->id;
                        
                        $mov->type='expense';
                        $mov->reason="Alistamiento de orden";
                        $mov->amount=$element["amount"];
                        $mov->stock_before=$pto->inventory->stock_available;

                        // $mov->stock_after=$pto->inventory->stock_available-$element["amount"];
                        $mov->stock_after=$pto->inventory->stock_available-$element["amount"];

                        $mov->author=Auth::id();
                        $mov->order_id=$orden->id;

                        //Guardo
                        if(!$mov->save()){
                            $this->dispatch('mostrarToast', 'Crear orden', 'Se ha generado un error al generar un movimiento, contacte a soporte');
                        }

                        //Ahora modifico el stock
                        $pto->inventory->stock_available=$mov->stock_after;

                        if(!$pto->inventory->save()){
                            $this->dispatch('mostrarToast', 'Crear orden', 'Se ha generado un error al actualizar stock, contacte a soporte');
                        }

                    }
                }


                $this->dispatch('mostrarToast', 'Crear orden', 'Se ha generado la pedido satisfactoriamente');

                $this->resetExcept("estudios");

                return redirect(route("orden.ver",$orden->id));

                
            }else{
                $this->dispatch('mostrarToast', 'Crear pedido', 'Ha ocurrido un error al generar la orden, contacte a soporte');
                registrarLog("Inventario","Órdenes","Crear","Ha intentado crear una orden con la siguiente información: ".json_encode($orden),false);
            }

            
        }

    }
    public function render()
    {
        return view('livewire.inventario.order');
    }
}
