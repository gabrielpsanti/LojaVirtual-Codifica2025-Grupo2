<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use Illuminate\Http\Request;

class TesteController extends Controller
{
    public function teste()
    {
        $categorias = Categoria::all();

        return view('chloe-teste.teste', compact('categorias'));
    }
}
