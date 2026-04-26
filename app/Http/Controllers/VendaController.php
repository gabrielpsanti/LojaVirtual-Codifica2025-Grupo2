<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Venda;
use App\Models\Usuario;
use App\Models\VendaProduto;

class VendaController extends Controller
{
    // Lista todas as vendas com filtros opcionais
    public function index(Request $request)
    {
        $query = Venda::with('cliente', 'produtos');

        // Filtro por cliente
        if ($request->filled('cliente')) {
            $query->whereHas('cliente', function ($q) use ($request) {
                $q->where('nome', 'like', '%' . $request->cliente . '%');
            });
        }

        // Filtro por data início
        if ($request->filled('data_inicio')) {
            $query->whereDate('data', '>=', $request->data_inicio);
        }

        // Filtro por data fim
        if ($request->filled('data_fim')) {
            $query->whereDate('data', '<=', $request->data_fim);
        }

        $vendas = $query->get();

        return view('admin.vendas.index', compact('vendas'));
    }

    // Mostra detalhes de uma venda específica
    public function show($id)
    {
        $venda = Venda::with('cliente', 'produtos.produto')->findOrFail($id);

        return view('admin.vendas.detalhes', compact('venda'));
    }
}
