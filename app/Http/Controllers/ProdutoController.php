<?php

namespace App\Http\Controllers;

use App\Models\Produto;
use Illuminate\Http\Request;

class ProdutoController extends Controller
{
    public function index() {
        $produtos = Produto::all();
        return view('admin.produtos.index', compact('produtos'));
    }

    public function create() {
        return view('admin.produtos.create');
    }

    public function store(Request $request) {

        $produto = new Produto();

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

    public function edit($id) {
        $produto = Produto::findOrFail($id);
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
}
