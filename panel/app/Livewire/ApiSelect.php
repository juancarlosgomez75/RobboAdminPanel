<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class ApiSelect extends Component
{
    public $environment; // Estado inicial

    public function updatedEnvironment($value)
    {
        // Aquí puedes manejar la lógica cuando se actualiza el valor
        // Por ejemplo, emitir un evento o cambiar una configuración
        if(Auth::user()->can_developt){
            if($value=="development"){
                session(['API_used' => 'development']);
                $this->dispatch('mostrarToast', 'Cambiar entorno', 'Ahora trabajarás en desarrollo, espera un momento por favor mientras se actualiza todo');
                $this->dispatch('refreshPage'); // lanza la señal
            }
            elseif($value== 'production'){
                session(['API_used' => 'production']);
                $this->dispatch('mostrarToast', 'Cambiar entorno', 'Ahora trabajarás en producción, espera un momento por favor mientras se actualiza todo');
                    // lógica de guardado
                $this->dispatch('refreshPage'); // lanza la señal
            }
        }

    }

    public function mount(){
        $this->environment = session('API_used',"development");
    }

    public function render()
    {
        return view('livewire.api-select');
    }
}
