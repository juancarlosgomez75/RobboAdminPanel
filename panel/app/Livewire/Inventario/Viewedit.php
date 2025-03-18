<?php

namespace App\Livewire\Inventario;

use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\ProductInventory;
use App\Models\ProductInventoryMovement;
use Livewire\Component;
use Livewire\WithPagination;

class Viewedit extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    public $producto;
    public $editing=false;

    public $name;
    public $description;
    public $category=-1;
    public $category_use;
    public $ref;

    public $stock=0;
    public $stockmin=0;

    public $activo=false;

    public $categorias;

    public $inventario;
    public $movimientos;

    public function activarEdicion(){
        $this->editing=true;
    }

    public function validar(){
        if(!(preg_match('/^[a-zA-Z0-9\/\-\áéíóúÁÉÍÓÚüÜñÑ\s]+$/', $this->name) && !empty(trim($this->name)))){
            $this->dispatch('mostrarToast', 'Editar producto', 'Error: El nombre del producto no es válido');
            return false;
        }
        elseif(!(preg_match('/^[a-zA-Z0-9\/\-\áéíóúÁÉÍÓÚüÜñÑ\s]+$/', $this->description) && !empty(trim($this->description)))){
            $this->dispatch('mostrarToast', 'Editar producto', 'Error: La descripción del producto no es válida');
            return false;
        }
        elseif(!empty(trim($this->ref)) && !preg_match('/^[a-zA-Z0-9\/\-\áéíóúÁÉÍÓÚüÜñÑ\s]+$/', $this->ref)) {

            $this->dispatch('mostrarToast', 'Editar producto', 'Error: La referencia no es válida');
            return false;
        }

        //Analizo la categoria
        if($this->category!="0" && $this->category!=null){
            if(ProductCategory::find(intval($this->category))){
                $this->category_use=$this->category;
            }else{
                $this->dispatch('mostrarToast', 'Editar producto', 'Error: La categoría no es válida');
                return false;
            }
        }else{
            $this->category_use=null;
        }

        //Analizo el stock mínimo
        if($this->stockmin<0 || !is_numeric($this->stockmin)){
            $this->dispatch('mostrarToast', 'Editar producto', 'Error: El stock mínimo no es válido');
            return false;
        }

        return true;


    }
    public function guardarEdicion(){
        if($this->validar()){
            //Edito la información, primero la del producto
            $this->producto=Product::find($this->producto->id);
            $this->producto->name= "$this->name";
            $this->producto->description= $this->description;
            $this->producto->category= $this->category_use;
            $this->producto->ref= $this->ref;

            //Almaceno
            if($this->producto->save()){
                //Ahora edito el inventario
                $this->inventario->stock_min=$this->stockmin;

                registrarLog("Inventario","Productos","Editar producto","Se ha modificado al producto #".$this->producto->id.", con información: ".json_encode($this->producto),true);

                if($this->inventario->save()){
                    $this->dispatch('mostrarToast', 'Editar producto', 'Se ha editado el producto correctamente');
                    $this->editing=false;

                    registrarLog("Inventario","Productos","Editar stock minimo","Se ha modificado el stock minimo del producto #".$this->producto->id.", con información: ".json_encode($this->inventario),true);
                }else{
                    $this->dispatch('mostrarToast', 'Editar producto', 'Error al modificar el inventario');
                    registrarLog("Inventario","Productos","Editar stock minimo","Se ha intentado modificar el stock minimo del producto #".$this->producto->id.", con información: ".json_encode($this->inventario),false);
                }
            }
            else{
                $this->dispatch('mostrarToast', 'Editar producto', 'Error al modificar el producto');
                registrarLog("Inventario","Productos","Editar producto","Se ha intentado modificar al producto #".$this->producto->id.", con información: ".json_encode($this->producto),false);
            }
        }
    }
    
    public function mount($producto){
        
        //Cargo las catergorias
        $this->categorias=ProductCategory::all();


        //Almaceno el prducto
        $this->producto=$producto;

        //Cargo el inventario
        $this->inventario=ProductInventory::find($this->producto->id);

        //Cargo las variables
        $this->name=$producto->name;
        $this->description=$producto->description;
        $this->category=$producto->category;
        $this->ref=$producto->ref;
        $this->activo=$producto->available;

        //Cargo el stock
        $this->stock=$this->producto->inventory->stock_available;
        $this->stockmin=$this->producto->inventory->stock_min;

    }

    public function render()
    {
        $movimientos=ProductInventoryMovement::where("inventory_id",$this->producto->inventory->id)->orderBy("created_at","desc")->paginate(20);
        return view('livewire.inventario.viewedit',["Movimientos"=> $movimientos]);
    }
}
