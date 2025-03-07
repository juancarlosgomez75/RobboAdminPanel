<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class StudyController extends Controller
{
    public function index(){
        return view("estudios.index");
    }
}
