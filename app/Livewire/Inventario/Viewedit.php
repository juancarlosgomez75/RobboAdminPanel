<?php

namespace App\Livewire\Inventario;

use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\ProductInventory;
use App\Models\ProductInventoryMovement;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;

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

    public $firmware;

    public $stock=0;
    public $stockmin=0;

    public $activo=false;

    public $categorias;

    public $inventario;
    public $movimientos;

    public function activarEdicion(){

        if(Auth::user()->rank < 4){
            $this->dispatch('mostrarToast', 'Editar producto', 'Error: No tienes los permisos para ejecutar esta acción');
            return false;
        }


        $this->editing=true;
    }

    public function validar(){
        if(Auth::user()->rank < 4){
            $this->dispatch('mostrarToast', 'Editar producto', 'Error: No tienes los permisos para ejecutar esta acción');
            return false;
        }


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

        elseif($this->firmware!="0" && $this->firmware!="1"){
            $this->dispatch('mostrarToast', 'Editar producto', 'Error: Selecciona si el producto usa Firmware ID o no');
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

    public function desactivar(){

        if(Auth::user()->rank < 4){
            $this->dispatch('mostrarToast', 'Desactivar producto', 'Error: No tienes los permisos para ejecutar esta acción');
            return false;
        }


        $this->producto=Product::find($this->producto->id);
        $this->producto->available=false;
        if($this->producto->save()){
            $this->dispatch('mostrarToast', 'Desactivar producto', 'Se ha desactivado el producto correctamente');
            registrarLog("Inventario","Productos","Desactivar producto","Se ha desactivado al producto #".$this->producto->id,true);
            $this->activo=false;
        }else{
            $this->dispatch('mostrarToast', 'Desactivar producto', 'No se ha logrado desactivar el producto');
            registrarLog("Inventario","Productos","Desactivar producto","Se ha intentado desactivar al producto #".$this->producto->id,false);
        }

    }

    public function activar(){
        if(Auth::user()->rank < 4){
            $this->dispatch('mostrarToast', 'Activar producto', 'Error: No tienes los permisos para ejecutar esta acción');
            return false;
        }

        $this->producto=Product::find($this->producto->id);
        $this->producto->available=true;
        if($this->producto->save()){
            $this->dispatch('mostrarToast', 'Activar producto', 'Se ha activado el producto correctamente');
            registrarLog("Inventario","Productos","Activar producto","Se ha activado al producto #".$this->producto->id,true);
            $this->activo=true;
        }else{
            $this->dispatch('mostrarToast', 'Activar producto', 'No se ha logrado activar el producto');
            registrarLog("Inventario","Productos","Activar producto","Se ha intentado activar al producto #".$this->producto->id,false);
        }

    }

    public function guardarEdicion(){
        if($this->validar()){
            //Edito la información, primero la del producto
            $this->producto=Product::find($this->producto->id);
            $this->producto->name= "$this->name";
            $this->producto->description= $this->description;
            $this->producto->category= $this->category_use;
            $this->producto->ref= $this->ref;
            $this->producto->use_firmwareid=filter_var($this->firmware, FILTER_VALIDATE_BOOLEAN);

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
        $this->firmware=$producto->use_firmwareid?"1":"0";

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
