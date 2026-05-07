<?php

namespace App\Http\Controllers\Cliente;

use App\Http\Controllers\Controller;
use App\Models\Produto;
use App\Models\Categoria;
use App\Repositories\ProdutoRepository;
use Illuminate\Http\Request;

class ProdutoController extends Controller
{
    private ProdutoRepository $produtoRepository;

    public function __construct(ProdutoRepository $produtoRepository)
    {
        $this->produtoRepository = $produtoRepository;
    }

    public function index(Request $request)
    {
        $produtosRecentes = Produto::query()->orderBy('produtos.created_at', 'desc')->take(15)->get();

        $produtosPromo = Produto::query()->whereNotNull('produtos.desconto_id')->orderBy('produtos.preco', 'asc')->take(15)->get();

        $categorias = Categoria::all();

        return view('cliente.index', compact('produtosRecentes', 'produtosPromo', 'categorias', ));
    }

    public function todos(Request $request)
    {
//        $query = Produto::query();
//
//        if ($request->nome) {
//            $query->where('nome', 'like', '%' . $request->nome . '%');
//        }
//
//        if ($request->categoria) {
//            $categoriaId = $this->produtoRepository->categoriaIdPelaRota($request->categoria);
//            $query->where('categoria_id', $categoriaId);
//        }

        $produtos = Produto::all();

        $categorias = Categoria::all();

        return view('cliente.produtos.todos', compact('produtos', 'categorias', ));
    }

    public function categoria(Request $request, string $categoria)
    {
        $query = Produto::query();

        $categoria = $this->produtoRepository->categoriaPelaRota($request->categoria);

        $produtos = $query->where('categoria_id', $categoria->id)->get();

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

    //Pesquisar(Request $request) : Retorna a view de pesquisa com a pesquisa sendo passada via query string (url)
    public function pesquisar(Request $request)
    {
        //A query() retorna um Query Builder do Laravel que prepara os filtros
        $sql = Produto::query();

        //to buscando atraves do que é digitado
        //Gabriel falou que essa forma de pesquisar usando o like não é profissional, mas deixei por não saber outro jeito kkk
        $sql->where('nome', 'like', '%' . $request->nome . '%');

        //vai retornar o que a query e o if buscaram do banco
        $produtos = $sql->get();

        //Colocar o nome da view da index
        return view('cliente.produtos.pesquisar', compact('produtos'));
    }


}
