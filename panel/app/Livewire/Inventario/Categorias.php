<?php

namespace App\Livewire\Inventario;

use App\Models\ProductCategory;
use Livewire\Component;

class Categorias extends Component
{
    public $name="";
    public $description="";

    public function validar(){

        if(!(preg_match('/^[a-zA-Z0-9\/\-\áéíóúÁÉÍÓÚüÜñÑ\s]+$/', $this->nombre) && !empty(trim($this->nombre)))){

            return false;
        }
    }
    public function guardar(){

        // Lógica de tu acción
        $this->dispatch('mostrarToast', 'Resultado', 'Categoría creada con éxito');



    }
    public function render()
    {
        //Busco las categorias
        $categorias=ProductCategory::all();
        return view('livewire.inventario.categorias',compact('categorias'));
    }
}
