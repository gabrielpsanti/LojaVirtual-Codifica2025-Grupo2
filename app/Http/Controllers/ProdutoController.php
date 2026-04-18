<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Produto;
// use App\Models\Categoria;

class ProdutoController extends Controller
{
    public function index(Request $request)
    {

        $produtos = Produto::all();
        // $categorias = Categoria::all();

        //Definir qual o nome da view que deve ser retornada aqui na index
//        return view('', compact('produtos', 'categorias'));
        return view('admin.produtos.index', compact('produtos'));

    }

    public function create() {
        return view('admin.produtos.create');
    }       

    public function store(Request $request) {

        $produto = new \App\Models\Produto();

        $produto->nome = $request->nome;
        $produto->preco = $request->preco;
        $produto->categoria = $request->categoria;
        $produto->descricao = $request->descricao;
        $produto->estoque = $request->estoque;
        if ($request->hasFile('imagem')) {
            $caminho = $request->file('imagem')->store('produtos', 'public');
            $produto->imagem = $caminho;
        } else {
            $produto->imagem = null;
        }

        $produto->save();

        return redirect()->route('admin.produtos.index');
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

    public function edit($id) {
        $produto = \App\Models\Produto::findOrFail($id);
        return view('admin.produtos.edit', compact('produto'));
    }

    public function update(Request $request, $id) {
        $produto = Produto::findOrFail($id);

        $produto->update($request->all());

        return redirect()->route('admin.produtos.index');
    }

    public function destroy($id) {
        Produto::destroy($id);
        return redirect()->route('admin.produtos.index');
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

}
