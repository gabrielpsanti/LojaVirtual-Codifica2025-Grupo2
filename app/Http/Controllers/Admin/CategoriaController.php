<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Categoria;
use Illuminate\Http\Request;

class CategoriaController extends Controller
{
    public function create() {
        return view('admin.categorias.create');
    }

    public function store(Request $request) {

        $validated = $request->validate([
            'nome' => 'required|string|min:3|max:255',
        ], [
            'nome.required' => 'O campo nome é obrigatório.',
            'nome.min' => 'O nome deve ter pelo menos 3 caracteres.',
        ]);

        $categoria = new Categoria();

        $categoria->nome = $validated['nome'];

        $categoria->save();

        return to_route('admin.categorias.salvar');
    }}
