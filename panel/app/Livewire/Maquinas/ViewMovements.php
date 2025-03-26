<?php

namespace App\Livewire\Maquinas;

use App\Models\MachineHistory;
use Livewire\Component;
use Livewire\WithPagination;

class ViewMovements extends Component
{
    use WithPagination;

    public $filtroFecha="";
    public $filtroAutor="";

    //Informacion de la maquina
    public $Maquina;
    public $Fecha="";
    public $Details="";
    public $Description= "";

    protected $paginationTheme = 'bootstrap';

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
            return $query->whereRaw("LOWER(created_at) LIKE ?", [strtolower($this->filtroFecha) . '%']);
        })
        ->when(!empty($this->filtroAutor), function ($query) {
            return $query->whereHas('author_info', function ($subQuery) {
                $subQuery->whereRaw("LOWER(name) LIKE ?", [strtolower($this->filtroAutor) . '%']);
            });
        })
        ->paginate(30);

        return view('livewire.maquinas.view-movements',compact('history'));
    }
}
