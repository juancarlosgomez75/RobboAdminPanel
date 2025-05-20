<?php

namespace App\Livewire\Inventario;

use App\Models\Product;
use Livewire\Component;

class Request extends Component
{
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

                $this->dispatch('mostrarToast', 'Añadir producto', 'Advertencia: Este producto ya está en el carrito');
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

        $this->dispatch('mostrarToast', 'Añadir producto', 'Producto añadido');

    }

    public function addExternal(){

        //Añado el producto
        $this->listProducts[]=[
            "id"=>null,
            "name"=>"",
            "amount"=>1,
            "internal"=>False
        ];

        $this->dispatch('mostrarToast', 'Añadir producto', 'Producto añadido');

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

    public function render()
    {
        return view('livewire.inventario.request');
    }
}
