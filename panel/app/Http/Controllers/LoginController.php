<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
USE Illuminate\Support\Facades\Auth;

class logincontroller extends Controller
{
    public function login(){
        if (Auth::check()) {
            return redirect(route("estudios.index"));
        }
        return view("login");
    }

    public function logout(Request $request){   
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect(route("login"));
    }
}