<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function accounts(){
        //Consulto las cuentas
        $accounts = User::where('rank', '<', 5)->with('rank')->get();



        return view("admin.accounts", compact("accounts"));
    }
}
