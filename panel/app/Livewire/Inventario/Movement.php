<?php

namespace App\Livewire\Inventario;

use App\Models\ProductInventoryMovement;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Movement extends Component
{
    public $inventory;

    public $reason="";
    public $amount=1;
    public $type="-1";

    public $details="";

    public function validar(){
        if(!(preg_match('/^[a-zA-Z0-9\/\-\áéíóúÁÉÍÓÚüÜñÑ\s]+$/', $this->reason) && !empty(trim($this->reason)))){
            $this->dispatch('mostrarToast', 'Crear movimiento', 'Error: La razón no es válida');
            return false;
        }
        elseif(($this->amount<1) || !(is_numeric($this->amount))){
            $this->dispatch('mostrarToast', 'Crear movimiento', 'Error: La cantidad no es válida');
            return false;
        }
        elseif($this->type!="0" && $this->type!= "1"){
            $this->dispatch('mostrarToast', 'Crear movimiento', 'Error: El tipo de movimiento no es válido');
            return false;
        }
        elseif(($this->amount>$this->inventory->stock_available) && ($this->type=="0")){
            $this->dispatch('mostrarToast', 'Crear movimiento', 'Error: Sólo hay '.$this->inventory->stock_available." unidades");
            return false;
        }
        elseif (!empty(trim($this->details)) && !preg_match('/^[a-zA-Z0-9\/\-_\.\,\$\#\@\!\?\%\&\*\(\)\[\]\{\}\áéíóúÁÉÍÓÚüÜñÑ\s]+$/', $this->details)){
            $this->dispatch('mostrarToast', 'Crear movimiento', 'Las observaciones no son válidas');
            return false;
        }

        return true;
    }
    public function guardar(){
        if($this->validar()){
            //Creo el movimiento
            $movimiento=new ProductInventoryMovement();

            //Asigno la info
            $movimiento->inventory_id=$this->inventory->id;

            $movimiento->reason=$this->reason;
            $movimiento->amount=$this->amount;
            $movimiento->stock_before=$this->inventory->stock_available;
            $movimiento->details=$this->details;

            if($this->type==1){
                $movimiento->type="income";
                $movimiento->stock_after=$this->inventory->stock_available+$this->amount;
            }else{
                $movimiento->type="expense";
                $movimiento->stock_after=$this->inventory->stock_available-$this->amount;
            }
            
            $movimiento->author=Auth::id();

            //Intento almacenar
            if($movimiento->save()){
                //Ahora intento modificar el inventario
                $this->inventory->stock_available=$movimiento->stock_after;
                if($this->inventory->save()){

                    registrarLog("Inventario","Productos","Crear movimiento","Se ha creado un movimiento con la siguiente información: ".json_encode($movimiento),true);

                    $this->dispatch('mostrarToast', 'Crear movimiento', 'Se ha creado el movimiento de forma satisfactoria');
                    $this->resetExcept("inventory");
                    return;
                }else{
                    $this->dispatch('mostrarToast', 'Crear movimiento', 'Se ha generado un error al actualizar el inventario');
                }
            }else{
                $this->dispatch('mostrarToast', 'Crear movimiento', 'Se ha generado un error al registrar el movimiento');
            }

            registrarLog("Inventario","Productos","Crear movimiento","Se ha intentado crear un movimiento con la siguiente información: ".json_encode($movimiento),false);


        }
    }

    public function mount($inventory){
        $this->inventory = $inventory;
    }
    public function render()
    {
        return view('livewire.inventario.movement');
    }
}
