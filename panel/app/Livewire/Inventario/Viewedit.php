<?php

namespace App\Livewire\Inventario;

use App\Models\ProductCategory;
use App\Models\ProductInventoryMovement;
use Livewire\Component;

class Viewedit extends Component
{
    protected $producto;
    public $editing=false;

    public $name;
    public $description;
    public $category=-1;
    public $ref;

    public $activo=false;

    public $categorias;

    public function activarEdicion(){
        $this->editing=true;
    }
    
    public function mount($producto){
        //Almaceno el prducto
        $this->producto=$producto;

        //Cargo las variables
        $this->name=$producto->name;
        $this->description=$producto->description;
        $this->category=$producto->category;
        $this->ref=$producto->ref;
        $this->activo=$producto->available;

        //Cargo las catergorias
        $this->categorias=ProductCategory::all();

    }

    public function render()
    {
        $movimientos=ProductInventoryMovement::where("inventory_id",$this->producto->inventory->id)->get();
        return view('livewire.inventario.viewedit',["Movimientos"=> $movimientos]);
    }
}
