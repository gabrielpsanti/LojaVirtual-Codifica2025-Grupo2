<?php

namespace App\Http\Controllers\Cliente;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CabecalhoController extends Controller
{
    public function faq()
    {
        $categorias = \App\Models\Categoria::all();

        return view('cliente.cabecalho.faq', compact('categorias'));
    }

    public function trocas()
    {
        $categorias = \App\Models\Categoria::all();

        return view('cliente.cabecalho.trocas', compact('categorias'));
    }

    public function quemSomos()
    {
        $categorias = \App\Models\Categoria::all();

        return view('cliente.cabecalho.quem-somos', compact('categorias'));
    }
}
