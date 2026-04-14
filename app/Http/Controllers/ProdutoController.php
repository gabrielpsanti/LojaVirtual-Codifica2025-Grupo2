<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Iluminate\Models\Produto;
use Iluminate\Models\Categoria;


class ProdutoController extends Controller
{
    public function index(Request $request) 
    {

        $produtos = Produto::all();
        $categorias = Categoria::all();

        //Definir qual o nome da view que deve ser retornada aqui na index
        return view('', compact('produtos', 'categorias'));

    }

    //Pesquisar(Request $request) : Retorna a view de pesquisa com a pesquisa sendo passada via query string (url)
    public function pesquisar(Request $request)
    {
        //A query() retorna um Query Builder do Laravel que prepara os filtros
        $sql = Produto::query();

        //to buscando atraves do que é digitado
        //Gabriel falou que essa forma de pesquisar usando o like não é profissional, mas deixei por não saber outro jeito kkk
        if ($request->nome) {
            $sql->where('nome', 'like', '%' . $request->nome . '%');
        }

        //vai retornar o que a query e o if buscaram do banco
        $produtos = $sql->get();
    
        //Colocar o nome da view da index
        return view('', compact('produtos'));
    }

    //Como é o cliente, o método só vai mostrar o produto na view de show
    public function show(Request $request, int $categoria_id)
    {
        $produto = Produto::findOrFail($id);
        
        if ($produto) {
            //defivir a view de show do produto
            return view('', compact('produto'));
        }

        return view('');
    }
    
}
