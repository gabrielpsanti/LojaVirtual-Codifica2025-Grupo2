<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class EnderecoController extends Controller
{
    public function index()
    {
//        $categorias = \App\Models\Categoria::all();

        return view('admin.enderecos.index');
    }
}
