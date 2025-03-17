<?php

namespace App\Livewire\Inventario;

use App\Models\ProductCategory;
use Livewire\Component;

class Categorias extends Component
{
    public function render()
    {
        //Busco las categorias
        $categorias=ProductCategory::all();
        return view('livewire.inventario.categorias',compact('categorias'));
    }
}
