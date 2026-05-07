<?php

namespace App\Http\Controllers\Cliente;

use App\Http\Controllers\Controller;
use App\Models\Produto;
use App\Models\Categoria;
use Illuminate\Http\Request;

class ProdutoController extends Controller
{
      public function index(Request $request)
    {
        $query = Produto::query();

        if ($request->nome) {
            $query->where('nome', 'like', '%' . $request->nome . '%');
        }

        if ($request->categoria) {
            $query->where('categoria', $request->categoria);
        }

        $produtos = $query->get();
        //        $categoriaFiltro = Produto::select('categoria')
//            ->whereNotNull('categoria')
//            ->where('categoria', '<>', '')
//            ->distinct()
//            ->orderBy('categoria')
//            ->pluck('categoria');

        $categorias = Categoria::all();

        return view('cliente.produtos.index', compact('produtos', 'categorias', ));

//         return view('components.cliente.produtos.index', compact('produtos', 'categorias'));
    }

    //aqui estou criando a função que mostra a view do produto indidualmente
    public function show(Request $request)
    {

        $produto = Produto::findOrFail($request->id);
        $categorias = Categoria::all();

        return view('cliente.produtos.show', compact('produto', 'categorias'));
    }


}
