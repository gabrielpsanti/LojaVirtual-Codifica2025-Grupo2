<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Models\Produto;
use App\Models\Venda;
use App\Models\Usuario;
use App\Models\Categoria;

class DashboardController extends Controller
{
    public function index()
    {
        $totalProdutos = Produto::count();
        $totalVendas = Venda::count();
        $totalUsuarios = Usuario::count();
        $totalCategorias = Categoria::count();

        return view('admin.dashboard.index', compact(
            'totalProdutos',
            'totalVendas',
            'totalUsuarios',
            'totalCategorias'
        ));
    }
}
