<?php

namespace App\Livewire;

use Livewire\Component;

class ApiSelect extends Component
{
    public $environment = 'dev'; // Estado inicial

    public function updatedEnvironment($value)
    {
        // Aquí puedes manejar la lógica cuando se actualiza el valor
        // Por ejemplo, emitir un evento o cambiar una configuración
        if($value=="dev"){
            session(['API_used' => 'development']);
            $this->dispatch('mostrarToast', 'Cambiar entorno', 'Ahora estás trabajando en desarrollo');
        }
        elseif($value== 'prod'){
            session(['API_used' => 'production']);
            $this->dispatch('mostrarToast', 'Cambiar entorno', 'Ahora estás trabajando en producción');
        }
    }

    public function render()
    {
        return view('livewire.api-select');
    }
}
