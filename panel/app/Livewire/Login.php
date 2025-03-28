<?php
namespace App\Livewire;

use Livewire\Component;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
USE Illuminate\Support\Facades\Auth;

class Login extends Component
{

    public $username="";
    public $password="";

    public $prueba="";

    public $error=false;
    public $response="";

    public function tryLogin(){
        //Intento validar
        $credenciales=[
            "username"=> $this->username,
            "password"=> $this->password,
            "activo"=>true
        ];

        if(Auth::attempt($credenciales)){
            session(['API_used' => 'development']);

            return redirect(route("panel.index"));
        }else{
            $this->error=true;
        }
    }
    public function mount(){
        $this->prueba=Hash::make("123");
    }
    public function render()
    {
        return view('livewire.login');
    }
}
