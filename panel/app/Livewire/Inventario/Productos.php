<?php

namespace App\Livewire\Inventario;

use App\Models\Product;
use Livewire\Component;

class Productos extends Component
{
    public function render()
    {
        //Busco los productos
        $productos=Product::all();
        
        return view('livewire.inventario.productos',compact('productos'));
    }
}
