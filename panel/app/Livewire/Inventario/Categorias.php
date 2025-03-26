<?php

namespace App\Livewire\Inventario;

use App\Models\ProductCategory;
use Livewire\Component;

class Categorias extends Component
{
    public $name="";
    public $description="";

    public $name_edit="";
    public $description_edit="";

    public $edit_cat;

    public function editar($id){
        //Primero trato de localizar el elemento
        $elemento=ProductCategory::find($id);

        if($elemento){
            $this->name_edit=$elemento->name;
            $this->description_edit=$elemento->description;
            $this->edit_cat=$elemento;
            $this->dispatch('abrirModalEdit');
        }else{
            $this->dispatch('mostrarToast', 'Editar categoría', 'Error: No se localiza la categoría');
        }
    }

    public function eliminar($id){
        $product = ProductCategory::find($id);

        if($product){
            if($product->delete()){
                $this->dispatch('mostrarToast', 'Eliminar categoría', 'Se ha eliminado la categoría');
                registrarLog("Inventario","Productos","Eliminar categoria","Se ha eliminado la categoría #".$id,true);
                return;
            }
            else{
                registrarLog("Inventario","Productos","Eliminar categoria","Se ha intentado eliminar la categoría #".$id,true);
            }
        }

        $this->dispatch('mostrarToast', 'Eliminar categoría', 'Error: No se ha eliminado la categoría');
    }

    public function validar_edit(){

        if(!(preg_match('/^[a-zA-Z0-9\/\-\áéíóúÁÉÍÓÚüÜñÑ\s]+$/', $this->name_edit) && !empty(trim($this->name_edit)))){
            $this->dispatch('mostrarToast', 'Editar categoría', 'Error: El nombre de la categoría no es válido');
            return false;
        }
        elseif(!(preg_match('/^[a-zA-Z0-9\/\-\áéíóúÁÉÍÓÚüÜñÑ\s]+$/', $this->description_edit) && !empty(trim($this->description_edit)))){
            $this->dispatch('mostrarToast', 'Editar categoría', 'Error: La descripción de la categoría no es válida');
            return false;
        }

        return true;
    }

    public function guardarCambios(){
        if($this->validar_edit()){
            //Analizo si el nombre cambia
            if($this->edit_cat->name!=$this->name_edit){
                //Analizo si hay algún elemento con ese nombre ya
                $existe=ProductCategory::where('name',$this->name_edit)->first();

                if($existe){
                    $this->dispatch('mostrarToast', 'Editar categoría', 'Error: Este nombre de categoría ya existe, usa otro');
                    $this->dispatch('cerrarModalEdit'); 
                    return;
                }
            }

            //Edito
            $this->edit_cat->name= $this->name_edit;
            $this->edit_cat->description= $this->description_edit;

            //Salvo
            if($this->edit_cat->save()){
                $this->dispatch('mostrarToast', 'Editar categoría', 'Se ha editado la categoría correctamente');
                registrarLog("Inventario","Categorías","Editar","Se ha editado la categoría #".$this->edit_cat->id.", con nombre: ".$this->edit_cat->name,true);
                
            }else{
                $this->dispatch('mostrarToast', 'Editar categoría', 'Error: contacte con soporte');
                registrarLog("Inventario","Categorías","Editar","Se ha intentado editar la categoría #".$this->edit_cat->id.", con nombre: ".$this->edit_cat->name,false);
            }
            $this->edit_cat="";

        }
        $this->dispatch('cerrarModalEdit'); 
    }

    public function validar(){

        if(!(preg_match('/^[a-zA-Z0-9\/\-\áéíóúÁÉÍÓÚüÜñÑ\s]+$/', $this->name) && !empty(trim($this->name)))){
            $this->dispatch('mostrarToast', 'Crear categoría', 'Error: El nombre de la categoría no es válido');
            return false;
        }
        elseif(!(preg_match('/^[a-zA-Z0-9\/\-\áéíóúÁÉÍÓÚüÜñÑ\s]+$/', $this->description) && !empty(trim($this->description)))){
            $this->dispatch('mostrarToast', 'Crear categoría', 'Error: La descripción de la categoría no es válida');
            return false;
        }

        return true;
    }
    public function guardar(){
        if($this->validar()){
            //Ahora valido que no exista
            $existe=ProductCategory::where('name',$this->name)->first();

            if($existe){
                $this->dispatch('mostrarToast', 'Crear categoría', 'Error: Este nombre de categoría ya existe, usa otro');
            }else{
                $categoria=new ProductCategory();
                $categoria->name=$this->name;
                $categoria->description=$this->description;

                if($categoria->save()){
                    $this->dispatch('mostrarToast', 'Crear categoría', 'Se ha creado la categoría con éxito');
                    registrarLog("Inventario","Categorías","Crear","Se ha creado la categoría #".$categoria->id.", con nombre: ".$categoria->name,true);
                    $this->dispatch('refrescarCategorias');
                }else{
                    registrarLog("Inventario","Categorías","Crear","Se ha intentado crear la categoría #".$categoria->id.", con nombre: ".$categoria->name,false);
                    $this->dispatch('mostrarToast', 'Crear categoría', 'Error: Póngase en contacto con soporte');
                }
            }
            
        }
    }

    public function render()
    {
        //Busco las categorias
        $categorias=ProductCategory::all();
        return view('livewire.inventario.categorias',compact('categorias'));
    }
}
