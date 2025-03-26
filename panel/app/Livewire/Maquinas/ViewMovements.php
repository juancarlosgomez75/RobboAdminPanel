<?php

namespace App\Livewire\Maquinas;

use App\Models\MachineHistory;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;



class ViewMovements extends Component
{
    use WithPagination;


    public $filtroFecha="";
    public $filtroAutor="";

    //Informacion de la maquina
    public $CreateDescription="";
    public $CreateDetails="";
    public $Maquina;
    public $Fecha="";
    public $Details="";
    public $Description= "";

    protected $paginationTheme = 'bootstrap';

    public function updatingFiltroFecha()
    {
        $this->resetPage();
    }

    public function updatingFiltroAutor()
    {
        $this->resetPage();
    }

    public function validar(){
        if(!(preg_match('/^[a-zA-Z0-9\/\-\áéíóúÁÉÍÓÚüÜñÑ\s]+$/', $this->CreateDescription) && !empty(trim($this->CreateDescription)))){
            $this->dispatch('mostrarToast', 'Crear movimiento', 'Error: La descripción no es válida');
            return false;
        }
        elseif(!(preg_match('/^[a-zA-Z0-9\/\-\áéíóúÁÉÍÓÚüÜñÑ\s]+$/', $this->CreateDetails) && !empty(trim($this->CreateDetails)))){
            $this->dispatch('mostrarToast', 'Crear movimiento', 'Error: Los detalles no son válidos');
            return false;
        }

        return true;
    }
    public function guardar(){
        if($this->validar()){
            $movimiento=new MachineHistory();

            //Cargo la info
            $movimiento->machine_id=$this->Maquina["ID"];
            $movimiento->description=$this->CreateDescription;
            $movimiento->details=$this->CreateDetails;
            $movimiento->author=Auth::user()->id;

            //Almaceno
            if($movimiento->save()){
                //Reporto
                $this->dispatch('mostrarToast', 'Crear movimiento', 'Se ha creado el movimiento correctamente');
                $this->CreateDescription="";
                $this->CreateDetails="";
                $this->resetPage();

                registrarLog("Producción","Maquinas","Crear movimiento","Se ha creado un movimiento con la siguiente información: ".json_encode($movimiento),true);
            }else{
                $this->dispatch('mostrarToast', 'Crear movimiento', 'Error almacenando, contacte con soporte');
                registrarLog("Producción","Maquinas","Crear movimiento","Se ha intentado crear un movimiento con la siguiente información: ".json_encode($movimiento),false);
            }


        }
    }

    public function showInfo($id){
        //Localizo el elemento
        $registro = MachineHistory::where("machine_id", "=", $this->Maquina["ID"])
        ->where("id", "=", $id)
        ->first();

        if($registro){
            $this->Fecha=$registro->created_at;
            $this->Description=$registro->description;
            $this->Details=$registro->details;
            $this->dispatch('abrirModalInfo');
        }
        
    }

    public function mount($Maquina){
        $this->Maquina=$Maquina;
    }

    public function render()
    {
        $history = MachineHistory::orderBy("created_at", "desc")
        ->when(!empty($this->filtroFecha), function ($query) {
            return $query->where("created_at", "=", Carbon::parse($this->filtroFecha));
        })
        ->when(!empty($this->filtroAutor), function ($query) {
            return $query->whereHas('author_info', function ($subQuery) {
                $subQuery->whereRaw("LOWER(name) LIKE ?", [strtolower($this->filtroAutor) . '%']);
            });
        })
        ->where("machine_id", "=", $this->Maquina["ID"])
        ->paginate(30);

        return view('livewire.maquinas.view-movements',compact('history'));
    }
}
