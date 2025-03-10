<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class StudyController extends Controller
{
    public function index(){
        return view("estudios.index");
    }

    public function create(){
        return view("estudios.create");
    }

    public function viewedit($idestudio){
        return view("estudios.viewedit");
    }
}
