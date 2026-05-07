<?php

namespace App\Http\Controllers\Cliente;

use App\Http\Controllers\Controller;
use App\Models\Produto;
use App\Models\Categoria;
use App\Repositories\ProdutoClienteRepository;
use Illuminate\Http\Request;

class ProdutoController extends Controller
{
    private ProdutoClienteRepository $produtoClienteRepository;
    public function __construct(ProdutoClienteRepository $produtoClienteRepository)
    {
        $this->produtoClienteRepository = $produtoClienteRepository;
    }

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

        return view('cliente.produtos.todos', compact('produtos', 'categorias', ));
    }

    public function todos(Request $request)
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

        return view('cliente.produtos.todos', compact('produtos', 'categorias', ));
    }

    public function categoria(Request $request, string $categoria)
    {
        $query = Produto::query();

        if ($request->nome) {
            $query->where('nome', 'like', '%' . $request->nome . '%');
        }

        $categoriaId = $this->produtoClienteRepository->categoriaIdPelaRota($request->categoria);
        $query->where('categoria_id', $categoriaId);

        $produtos = $query->get();

        $categorias = Categoria::all();

        return view('cliente.produtos.categoria', compact('produtos', 'categorias', ));
    }

    //aqui estou criando a função que mostra a view do produto indidualmente
    public function show(Request $request)
    {

        $produto = Produto::findOrFail($request->id);
        $categorias = Categoria::all();

        return view('cliente.produtos.show', compact('produto', 'categorias'));
    }


}
