<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ClasificacionesController extends Controller
{

    public function index()
{
    $titulo = "Ranking";
    return view('clasificaciones.clasificaciones')->with('titulo', $titulo);
}


}
