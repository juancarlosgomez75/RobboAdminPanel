<?php

namespace App\Livewire\Inventario;

use App\Models\Product;
use App\Models\Request as ModelsRequest;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class Request extends Component
{
    //Información del pedido
    public $empresa="";
    public $ciudad="";
    public $direccion="";
    public $telefono="";
    public $nombre="";
    public $fecha="";
    public $observaciones="";
    
    //Información de los productos
    public $listProducts=[];
    public $addingProduct=false;

    public $product_name="";
    public $product_amount=1;

    //Información de los detalles
    public $details="";

    public $searchResults;
    public $finded=false;

    public function startAdding(){
        $this->addingProduct=true;
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

                $this->dispatch('mostrarToast', 'Añadir producto', 'Advertencia: Este producto interno ya está en el carrito');
                return;
            }
        }

        //Añado el producto
        $this->listProducts[]=[
            "id"=>$busqueda->id,
            "name"=>$busqueda->name,
            "amount"=>1,
            "internal"=>True
        ];

        $this->dispatch('mostrarToast', 'Añadir producto', 'Producto interno añadido');

    }

    public function addExternal(){

        //Añado el producto
        $this->listProducts[]=[
            "id"=>null,
            "name"=>"",
            "amount"=>1,
            "internal"=>False
        ];

        $this->dispatch('mostrarToast', 'Añadir producto', 'Producto externo añadido');

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

    public function validar(){
        //Analizo los campos
        if(!(preg_match('/^[a-zA-ZÀ-ÿ0-9#\-.\s]+$/', $this->empresa) && !empty(trim($this->empresa)))){
            $this->dispatch('mostrarToast', 'Crear pedido', 'El nombre de eempresa no es válido');
            return false;
        }
        elseif($this->direccion!="" && !(preg_match('/^[a-zA-Z0-9#\-. áéíóúÁÉÍÓÚüÜñÑ]+$/', $this->direccion) && !empty(trim($this->direccion)))){
            $this->dispatch('mostrarToast', 'Crear pedido', 'La dirección no es válida');
            return false;
        }
        elseif($this->ciudad!="" && !(preg_match('/^[a-zA-Z0-9\/\-\áéíóúÁÉÍÓÚüÜñÑ\s]+$/', $this->ciudad) && !empty(trim($this->ciudad)))){
            $this->dispatch('mostrarToast', 'Crear pedido', 'La ciudad no es válida');
            return false;
        }
        elseif($this->nombre!="" && !(preg_match('/^[a-zA-ZÀ-ÿ0-9#\-.\s]+$/', $this->nombre) && !empty(trim($this->nombre)))){
            $this->dispatch('mostrarToast', 'Crear pedido', 'El nombre de encargado no es válido');
            return false;
        }
        elseif($this->telefono!="" && !(preg_match('/^[\d+\-]+$/', $this->telefono) && !empty(trim($this->telefono)))){
            $this->dispatch('mostrarToast', 'Crear pedido', 'El teléfono de contacto no es válido');
            return false;
        }
        elseif (empty($this->fecha) || !Carbon::hasFormat($this->fecha, 'Y-m-d') ||Carbon::parse($this->fecha)->lt(Carbon::today())) {
            $this->dispatch('mostrarToast', 'Crear pedido', 'La fecha tentativa de entrega no es válida o es anterior a hoy');
            return false;
        }

        //Ahora valido si tiene o no producto añadidos
        if(empty($this->listProducts)){
            $this->dispatch('mostrarToast', 'Crear pedido', 'El carrito de compras está vacío');
            return false;
        }

        //Ahora valido las observaciones
        if (!empty(trim($this->observaciones)) && !preg_match('/^[a-zA-Z0-9\/\-_\.\,\$\#\@\!\?\%\&\*\(\)\[\]\{\}\áéíóúÁÉÍÓÚüÜñÑ\s]+$/', $this->observaciones)){
            $this->dispatch('mostrarToast', 'Crear pedido', 'Las observaciones no son válidas');
            return false;
        }

        //Valido los productos
        foreach($this->listProducts as $index=>$product){

            //Analizo si es de tipo externo o interno
            if($product["internal"]){
                //Busco el producto
                $buscado=Product::find($product['id']);

                if(!$buscado){
                    $this->dispatch('mostrarToast', 'Crear pedido', 'El producto: '.$product["name"].' no fue encontrado');
                    return false;
                }
            }else{
                if(!(preg_match('/^[a-zA-ZÀ-ÿ0-9#\-.\s]+$/', $product["name"]) && !empty(trim($product["name"])))){
                    $this->dispatch('mostrarToast', 'Crear pedido', 'El producto #'.($index+1).' no tiene un nombre válido');
                    return false;
                }
            }

            if($product["amount"]<1){
                $this->dispatch('mostrarToast', 'Crear pedido', 'El producto #'.($index+1).' no tiene una cantidad válida');
                return false;
            }
        }

        return true;
    }

    public function crear(){

        if($this->validar()){
            //Creo el objeto
            $orden=new ModelsRequest();

            //Cargo la información básica
            $orden->enterprise=$this->empresa;
            $orden->city=$this->ciudad;
            $orden->address=$this->direccion;
            $orden->name=$this->nombre;
            $orden->phone=$this->telefono;

            //Genero los detalles de creación
            $orden->created_by=Auth::id();
            $orden->creation_notes=$this->observaciones;
            $orden->creation_list=json_encode($this->listProducts);
            $orden->tentative_delivery_date=$this->fecha;

            //Intento guardar
            if($orden->save()){

                registrarLog("Inventario","Pedidos","Crear","Ha creado un pedido con la siguiente información: ".json_encode($orden),true);

                $this->dispatch('mostrarToast', 'Crear pedido', 'Se ha generado el pedido satisfactoriamente');

                $this->reset();

                return redirect(route("pedido.ver",$orden->id));

                
            }else{
                $this->dispatch('mostrarToast', 'Crear pedido', 'Ha ocurrido un error al generar el pedido, contacte a soporte');
                registrarLog("Inventario","Órdenes","Crear","Ha intentado crear un pedido con la siguiente información: ".json_encode($orden),false);
            }

            
        }

    }

    public function render()
    {
        return view('livewire.inventario.request');
    }
}
