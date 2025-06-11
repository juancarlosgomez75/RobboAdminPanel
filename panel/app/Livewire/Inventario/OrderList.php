<?php

namespace App\Livewire\Inventario;

use App\Models\ProductOrder;
use Livewire\Component;
use Livewire\WithPagination;

class OrderList extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $filtroNombre = "";
    public $filtroEstudio = "";
    public $filtroFecha = "";
    public $filtroEstado="availables";
    public $filtroTipo="0";
    public $filtrosActivos = false;

    public $listadoEstudios=[];

    public function switchFiltros()
    {
        $this->filtrosActivos = !$this->filtrosActivos;

        if (!$this->filtrosActivos) {
            $this->filtroNombre = "";
            $this->filtroFecha = "";
            $this->filtroEstado="availables";
            $this->filtroTipo="0";
            $this->filtroEstudio = "";
        }
    }

    public function mount(){
        $data_send=[
            'Branch' => 'Server',
            'Service' => 'PlatformUser',
            'Action' => 'StudyList',
            'Data' => ["UserId" => "1"]
        ];
        $data=sendBack($data_send);

        //Analizo si es válido lo que necesito
        if (isset($data['Status'])) {
            //Analizo si el status es true
            if($data["Status"]){
                #Ahora indexo todo para búsqueda más fácil
                foreach ($data["ListStudyData"] as $Study) {
                    $this->listadoEstudios[$Study['Id']] = $Study['StudyName'];
                }
            }
        }
    }

    public function render()
    {
        $idEstudio = null;
        if (!empty($this->filtroEstudio)) {
            // Busco el estudio que se asemeje (insensible a mayúsculas)
            foreach ($this->listadoEstudios as $ide => $namee) {
                if (strpos(strtolower($namee), strtolower($this->filtroEstudio)) !== false) {
                    $idEstudio = $ide;
                    break; // rompe al encontrar coincidencia
                }
            }
        }
        $pedidos = ProductOrder::orderBy("created_at", "desc")
            ->when(!empty($this->filtroFecha), function ($query) {
                return $query->whereRaw("TO_CHAR(created_at, 'YYYY-MM-DD HH24:MI:SS') LIKE ?", [$this->filtroFecha . '%']);
            })
            ->when(!empty($this->filtroNombre), function ($query) {
                return $query->whereRaw("name LIKE ?", [strtolower($this->filtroNombre) . '%']);
            })
            ->when(true, function ($query) {
                switch ($this->filtroEstado) {
                    case "all":
                        return $query;
                    case "availables":
                        return $query->where("status", "!=", "canceled")->where("finished", false);
                    case "cancelled":
                        return $query->where("status", "canceled");
                    case "finished":
                        return $query->where("finished", true);
                    default:
                        return $query->where("status", $this->filtroEstado)
                                    ->where("status", "!=", "canceled")
                                    ->where("finished", false);
                }
            })
            ->when($this->filtroTipo == "-1" || $this->filtroTipo == "1", function ($query) {
                return $query->where("type", ($this->filtroTipo == "-1") ? 'shipping' : 'collection');
            })
            ->when($idEstudio !== null, function ($query) use ($idEstudio) {
                return $query->where("study_id", $idEstudio);
            })
            ->paginate(20);

        // Agregar campo study_name a cada pedido
        foreach ($pedidos as $pedido) {
            $studyId = $pedido->study_id ?? null; // el campo de relación
            $pedido->study_name = $this->listadoEstudios[$studyId] ?? null;
        }


        return view('livewire.inventario.order-list',compact("pedidos","idEstudio"));
    }
}
