<?php

namespace App\Livewire\Inventario;

use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\ProductInventory;
use Livewire\Component;

class Productos extends Component
{
    public $name;
    public $description;
    public $category=-1;

    protected $category_use;
    public $ref;

    public function validar(){
        if(!(preg_match('/^[a-zA-Z0-9\/\-\áéíóúÁÉÍÓÚüÜñÑ\s]+$/', $this->name) && !empty(trim($this->name)))){
            $this->dispatch('mostrarToast', 'Crear producto', 'Error: El nombre del producto no es válido');
            return false;
        }
        elseif(!(preg_match('/^[a-zA-Z0-9\/\-\áéíóúÁÉÍÓÚüÜñÑ\s]+$/', $this->description) && !empty(trim($this->description)))){
            $this->dispatch('mostrarToast', 'Crear producto', 'Error: La descripción del producto no es válida');
            return false;
        }
        elseif(!empty(trim($this->ref)) && !preg_match('/^[a-zA-Z0-9\/\-\áéíóúÁÉÍÓÚüÜñÑ\s]+$/', $this->ref)) {

            $this->dispatch('mostrarToast', 'Crear producto', 'Error: La referencia no es válida');
            return false;
        }

        //Analizo la categoria
        if($this->category!="0"){
            if(ProductCategory::find(intval($this->category))){
                $this->category_use=$this->category;
            }else{
                $this->dispatch('mostrarToast', 'Crear producto', 'Error: La categoría no es válida: '.$this->category);
                return false;
            }
        }else{
            $this->category_use=null;
        }

        return true;
    }

    public function guardar(){
        if($this->validar()){
            //Creo el nuevo producto
            $producto=new Product();

            //Asigno los valores
            $producto->name=$this->name;
            $producto->description=$this->description;
            $producto->ref=$this->ref;
            $producto->category=$this->category_use;

            if($producto->save()){
                //Creo el inventario
                $inventario=new ProductInventory();
                $inventario->product_id=$producto->id;

                if($inventario->save()){
                    $this->dispatch('mostrarToast', 'Crear producto', 'Se ha creado el producto');
                }else{
                    $this->dispatch('mostrarToast', 'Crear producto', 'Se ha producido un error al crear el inventario, contacte con soporte');
                }
            }else{
                $this->dispatch('mostrarToast', 'Crear producto', 'Se ha producido un error al crear el producto, contacte con soporte');
            }
            
        }
    }
    public function render()
    {
        //Busco los productos
        $productos=Product::all();

        //Busco las categorías
        $categorias=ProductCategory::all();

        return view('livewire.inventario.productos',compact('productos','categorias'));
    }
}
