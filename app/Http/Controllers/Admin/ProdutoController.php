<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Produto;
use App\Models\Categoria;
use Illuminate\Http\Request;

// use App\Models\Categoria;

class ProdutoController extends Controller
{
    public function index(Request $request)
    {
        $query = Produto::query();

        if ($request->nome) {
            $query->where('nome', 'like', '%' . $request->nome . '%');
        }

        if ($request->categoria) {
            $query->where('categoria_id', $request->categoria);
        }

        $produtos = $query->get();
//        $categoriaFiltro = Produto::select('categoria')
//            ->whereNotNull('categoria')
//            ->where('categoria', '<>', '')
//            ->distinct()
//            ->orderBy('categoria')
//            ->pluck('categoria');

        $categorias = Categoria::all();

        return view('admin.produtos.index', compact('produtos', 'categorias', ));

    }

    public function create() {
        $categorias = Categoria::all();
        return view('admin.produtos.create', compact('categorias'));
    }

    public function store(Request $request) {

        $validated = $request->validate([
            'nome' => 'required|string|min:3|max:255',
            'preco' => 'required|numeric|min:0',
            'categoria_id' => 'required|integer|exists:categorias,id',
            'descricao' => 'required|string',
            'quantidade' => 'required|integer|min:0',
            'imagem' => 'required|image|max:2048',
        ], [
            'nome.required' => 'O campo nome é obrigatório.',
            'nome.min' => 'O nome deve ter pelo menos 3 caracteres.',
            'preco.required' => 'O campo preço é obrigatório.',
            'categoria_id.required' => 'O campo categoria é obrigatório.',
            'categoria_id.integer' => 'A categoria selecionada é inválida.',
            'categoria_id.exists' => 'A categoria selecionada não existe.',
            'descricao.required' => 'O campo descrição é obrigatório.',
            'quantidade.required' => 'O campo estoque é obrigatório.',
            'preco.numeric' => 'O campo preço deve ser um número.',
            'preco.min' => 'O preço não pode ser negativo.',
            'quantidade.integer' => 'O campo estoque deve ser um número inteiro.',
            'quantidade.min' => 'O estoque não pode ser negativo.',
            'imagem.required' => 'A imagem é obrigatória.',
            'imagem.image' => 'O arquivo de imagem deve ser uma imagem válida.',
            'imagem.max' => 'A imagem não pode ter mais de 2 MB.',
        ]);

        $produto = new \App\Models\Produto();

        $produto->nome = $validated['nome'];
        $produto->preco = $validated['preco'];
        $produto->categoria_id = $validated['categoria_id'];
        $produto->descricao = $validated['descricao'];
        $produto->quantidade = $validated['quantidade'];
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
        $categorias = Categoria::all();
        return view('admin.produtos.edit', compact('produto', 'categorias'));
    }

    public function update(Request $request, $id) {
        $produto = Produto::findOrFail($id);

        $validated = $request->validate([
            'nome' => 'required|string|max:255',
            'preco' => 'required|numeric|min:0',
            'categoria_id' => 'required|integer|exists:categorias,id',
            'descricao' => 'required|string',
            'quantidade' => 'required|integer|min:0',
            'imagem' => 'nullable|image|max:2048',
        ], [
            'nome.required' => 'O campo nome é obrigatório.',
            'preco.required' => 'O campo preço é obrigatório.',
            'categoria_id.required' => 'O campo categoria é obrigatório.',
            'categoria_id.integer' => 'A categoria selecionada é inválida.',
            'categoria_id.exists' => 'A categoria selecionada não existe.',
            'descricao.required' => 'O campo descrição é obrigatório.',
            'quantidade.required' => 'O campo estoque é obrigatório.',
            'preco.numeric' => 'O campo preço deve ser um número.',
            'preco.min' => 'O preço não pode ser negativo.',
            'quantidade.integer' => 'O campo estoque deve ser um número inteiro.',
            'quantidade.min' => 'O estoque não pode ser negativo.',
            'imagem.image' => 'O arquivo de imagem deve ser uma imagem válida.',
            'imagem.max' => 'A imagem não pode ter mais de 2 MB.',
        ]);

        $produto->nome = $validated['nome'];
        $produto->preco = $validated['preco'];
        $produto->categoria_id = $validated['categoria_id'];
        $produto->descricao = $validated['descricao'];
        $produto->quantidade = $validated['quantidade'];

        if ($request->hasFile('imagem')) {
            $caminho = $request->file('imagem')->store('produtos', 'public');
            $produto->imagem = $caminho;
        }

        $produto->save();

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
