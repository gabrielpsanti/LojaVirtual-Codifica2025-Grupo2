<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Categoria;
use Illuminate\Http\Request;

class CategoriaController extends Controller

{
    public function index(Request $request)
    {
        $query = Categoria::query();

        if ($request->nome) {
            $query->where('nome', 'like', '%' . $request->nome . '%');
        }

        $categorias = $query->get();

        return view('admin.categorias.index', compact('categorias'));
    }

    public function create()
    {
        return view('admin.categorias.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nome' => 'required|string|min:3|max:255',
        ], [
            'nome.required' => 'O campo nome é obrigatório.',
            'nome.min' => 'O nome deve ter pelo menos 3 caracteres.',
        ]);

        Categoria::create([
            'nome' => $validated['nome']
//            'nome' => $request->nome
        ]);

        return redirect()->route('admin.categorias.index');
    }

    public function edit($id)
    {
        $categoria = Categoria::findOrFail($id);
        return view('admin.categorias.edit', compact('categoria'));
    }

    public function update(Request $request, $id)
    {
        $categoria = Categoria::findOrFail($id);

        $validated = $request->validate([
            'nome' => 'required|string|min:3|max:255',
        ], [
            'nome.required' => 'O campo nome é obrigatório.',
            'nome.min' => 'O nome deve ter pelo menos 3 caracteres.',
        ]);

        $categoria->update([
            'nome' => $validated['nome']
//            'nome' => $request->nome
        ]);

        return redirect()->route('admin.categorias.index');
    }

    public function destroy($id)
    {
        Categoria::findOrFail($id)->delete();
        return redirect()->route('admin.categorias.index');
    }
}
