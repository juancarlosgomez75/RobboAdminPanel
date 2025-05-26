<?php

namespace App\Livewire\Inventario;

use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\ProductInventory;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class Productos extends Component
{
    public $filtroNombre = "";
    public $filtroCat = "";
    public $filtroEstado="1";

    public $name;
    public $description;
    public $category=-1;

    public $firmware=-1;

    protected $category_use;
    public $ref;

    public $filtrosActivos=False;

    public $ordenarPor = "name";
    public $ordenarDesc = true;

    protected $listeners = ['refrescarCategorias'];

    public function ordenarBy($filtro){
        //Analizo si cambia es la columna o la dirección
        if($filtro == "name"){
            if($filtro != $this->ordenarPor){
                $this->ordenarPor = $filtro;
                $this->ordenarDesc = true;
            }else{
                $this->ordenarDesc = !$this->ordenarDesc;
            } 
        }
    }

    public function switchFiltros()
    {
        $this->filtrosActivos = !$this->filtrosActivos;
        
        if (!$this->filtrosActivos) {
            $this->filtroCat="";
            $this->filtroNombre="";
        }
    }
    

    public function refrescarCategorias(){
        $this->render();
    }

    public function validar(){
        //Pregunta el rango
        if(Auth::user()->rank < 4){
            $this->dispatch('mostrarToast', 'Crear producto', 'Error: No tienes los permisos para ejecutar esta acción');
            return false;
        }



        if(!(preg_match('/^[a-zA-Z0-9\/\-\áéíóúÁÉÍÓÚüÜñÑ\s]+$/', $this->name) && !empty(trim($this->name)))){
            $this->dispatch('mostrarToast', 'Crear producto', 'Error: El nombre del producto no es válido o está vacío');
            return false;
        }
        elseif (!empty(trim($this->description)) && !preg_match('/^[a-zA-Z0-9\/\-\áéíóúÁÉÍÓÚüÜñÑ\s]+$/', $this->description)) {
            $this->dispatch('mostrarToast', 'Crear producto', 'Error: La descripción del producto no es válida');
            return false;
        }
        elseif(!empty(trim($this->ref)) && !preg_match('/^[a-zA-Z0-9\/\-\áéíóúÁÉÍÓÚüÜñÑ\s]+$/', $this->ref)) {

            $this->dispatch('mostrarToast', 'Crear producto', 'Error: La referencia no es válida o está vacía');
            return false;
        }
        elseif($this->firmware!="0" && $this->firmware!="1"){
            $this->dispatch('mostrarToast', 'Crear producto', 'Error: Selecciona si el producto usa Firmware ID o no');
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
            $producto->use_firmwareid=filter_var($this->firmware, FILTER_VALIDATE_BOOLEAN);

            if($producto->save()){
                //Creo el inventario
                $inventario=new ProductInventory();
                $inventario->product_id=$producto->id;

                if($inventario->save()){
                    $this->dispatch('mostrarToast', 'Crear producto', 'Se ha creado el producto');
                    $this->reset();
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
        if($this->filtroEstado=="1" || $this->filtroEstado=="0"){
            $productos=Product::where('available',"=",($this->filtroEstado=="1"))->get();
        }else{
            //Busco los productos
            $productos=Product::all();
        }



        // Aplicar filtro por nombre (si hay)
        if (!empty($this->filtroNombre)) {
            $productos = $productos->filter(function ($producto) {
                return stripos($producto->name, $this->filtroNombre) !== false;
            });
        }

        // Aplicar filtro por categoría (si hay)
        if (!empty($this->filtroCat)) {
            $productos = $productos->filter(function ($producto) {
                return stripos(optional($producto->category_info)->name, $this->filtroCat) !== false;
            });
        }


        //Aplico los filtros a los modelos
        if($this->ordenarPor=="name"){
            if($this->ordenarDesc){
                $productos = $productos->sortBy('name');
            }else{
                $productos = $productos->sortByDesc('name');
            }
        }

        //Busco las categorías
        $categorias=ProductCategory::all();

        return view('livewire.inventario.productos',compact('productos','categorias'));
    }
}
