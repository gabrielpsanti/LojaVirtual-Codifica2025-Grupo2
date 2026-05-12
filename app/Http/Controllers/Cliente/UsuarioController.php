<?php

namespace App\Http\Controllers\Cliente;

use App\Http\Controllers\Controller;
use App\Models\Categoria;
use Illuminate\Http\Request;

class UsuarioController extends Controller
{
    public function index(Request $request)
    {
        $pedidos = auth()->user()->vendas();

//        $endereco = auth()->user()->enderecos()->where('id', 1);
//        if (auth()->user()->enderecos()->exists()) {
//            $endereco = auth()->user()->enderecos()->first();
//        } else {
//            $endereco = null;
//        }


        $categorias = Categoria::all();

        $usuario = auth()->user();

        return view('cliente.usuario.conta', compact('pedidos', 'categorias', 'usuario'));
    }
}
