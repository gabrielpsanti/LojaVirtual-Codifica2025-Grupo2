<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Categoria;
use App\Repositories\CategoriaRepository;
use Illuminate\Http\Request;

class CategoriaController extends Controller
{
    private CategoriaRepository $categoriaRepository;

    public function __construct(CategoriaRepository $categoriaRepository)
    {
        $this->categoriaRepository = $categoriaRepository;
    }

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

        $rotaCategoria = $this->categoriaRepository->nomeDaRota($request->nome);

        Categoria::create([
            'nome' => trim($validated['nome']),
            'rota' => $rotaCategoria
        ]);

        return to_route('admin.categorias.index');
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

        $rotaCategoria = $this-$categoriaRepository->nomeDaRota($request->nome);

        $categoria->update([
            'nome' => trim($validated['nome']),
            'rota' => $rotaCategoria
        ]);

        return to_route('admin.categorias.index');
    }

    public function destroy($id)
    {
        Categoria::findOrFail($id)->delete();
        return to_route('admin.categorias.index');
    }
}
