<?php

namespace App\Livewire\Estudios;

use App\Models\PendingStudy;
use Livewire\Component;
use Illuminate\Support\Collection;

class Listado extends Component
{
    public $filtroNombre = "";
    public $filtroCiudad = "";

    public $filtroEstado="1";
    public $filtrosActivos = false;

    public $ordenarPor = "name";
    public $ordenarDesc = true;
    public $datos;

    public function ordenarBy($filtro){
        //Analizo si cambia es la columna o la dirección
        if($filtro == "name" || $filtro == "city" || $filtro=="id"){
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
            $this->filtroNombre = "";
            $this->filtroCiudad = "";
        }
    }


    public function mount($datos)
    {
        //Recorro loa datos
            foreach($datos as $index => $dato){
                $pendiente = PendingStudy::where("id_study", "=", $dato['Id'])->exists();
                $datos[$index]["StudyPending"] = $pendiente;
            }

        $this->datos = $datos;

    }


    public function render()
    {
        // Filtrar los datos
        $filtrados = array_filter($this->datos, function ($dato) {
            $nombreCoincide = empty($this->filtroNombre) || stripos($dato["StudyName"], $this->filtroNombre) !== false;
            $ciudadCoincide = empty($this->filtroCiudad) || stripos($dato["City"], $this->filtroCiudad) !== false;

            // // Convertimos filtroEstado en booleano si es 0 o 1
            // $estadoFiltrado = $this->filtroEstado == "-1" ? null : (bool) $this->filtroEstado;

            // $estadoCoincide = is_null($estadoFiltrado) || (isset($dato["Active"]) && (bool) $dato["Active"] === $estadoFiltrado);

            // Evaluamos según el estado:
            if ($this->filtroEstado == "-1") {
                $estadoCoincide = true; // Mostrar todos
            } elseif ($this->filtroEstado == "0") {
                // Inactivos y que no estén en pendiente
                $estadoCoincide = isset($dato["Active"], $dato["StudyPending"]) && !$dato["Active"] && !$dato["StudyPending"];
            } elseif ($this->filtroEstado == "1") {
                // Activos o que estén en pendiente
                $estadoCoincide = (isset($dato["Active"]) && $dato["Active"]) || (isset($dato["StudyPending"]) && $dato["StudyPending"]);
            } elseif ($this->filtroEstado == "2") {
                // Solo los pendientes
                $estadoCoincide = isset($dato["StudyPending"]) && $dato["StudyPending"];
            } else {
                $estadoCoincide = true; // Valor inesperado, por seguridad se muestran todos
            }


            return $nombreCoincide && $ciudadCoincide  && $estadoCoincide;
        });


        //Ordeno según el tipo de ordenación
        if($this->ordenarPor=="name"){
            if($this->ordenarDesc){
                usort($filtrados, function ($a, $b) {
                    return strcmp($a["StudyName"], $b["StudyName"]);
                });
            }else{
                usort($filtrados, function ($a, $b) {
                    return strcmp($b["StudyName"], $a["StudyName"]);
                });
            }
        }else if($this->ordenarPor=="city"){
            if($this->ordenarDesc){
                usort($filtrados, function ($a, $b) {
                    return strcmp($a["City"], $b["City"]);
                });
            }else{
                usort($filtrados, function ($a, $b) {
                    return strcmp($b["City"], $a["City"]);
                });
            }
        }else if($this->ordenarPor=="id"){
            if ($this->ordenarDesc) {
                usort($filtrados, function ($a, $b) {
                    return intval($a["Id"]) <=> intval($b["Id"]);
                });
            } else {

                usort($filtrados, function ($a, $b) {
                    return intval($b["Id"]) <=> intval($a["Id"]);
                });
            }
        }

        return view('livewire.estudios.listado', [
            'texto' => $this->filtroNombre,
            'filtroOn' => $this->filtrosActivos,
            'datosUsar' => $filtrados
        ]);

    }
}
