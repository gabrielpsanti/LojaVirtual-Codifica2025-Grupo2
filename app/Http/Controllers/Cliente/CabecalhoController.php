<?php

namespace App\Http\Controllers\Cliente;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CabecalhoController extends Controller
{
    public function faq()
    {
        return view('cliente.cabecalho.faq');
    }

    public function trocas()
    {
        return view('cliente.cabecalho.trocas');
    }

    public function quemSomos()
    {
        return view('cliente.cabecalho.quem-somos');
    }
}