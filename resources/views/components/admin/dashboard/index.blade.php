@props(['totalProdutos', 'totalVendas', 'totalUsuarios', 'totalCategorias'])
<link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
<link rel="stylesheet" href="{{ asset('css/base.css') }}">

<div class="principal">
    <h1>Dashboard</h1>

    <div class="principal-cartao">
        <!-- Produtos -->
        <div class="principal-cartao-produto">
            <div class="principal-cartao-produto-info">
                <h3>Produtos em Estoque</h3>
                <p class="principal-cartao-produto-info-number">{{ $totalProdutos }}</p>
            </div>
            <a href="{{ route('admin.produtos.index') }}" class="principal-cartao-produto-link">Ver Produtos</a>
        </div>

        <!-- Vendas -->
        <div class="principal-cartao-venda">
            <div class="principal-cartao-venda-info">
                <h3>Vendas Realizadas</h3>
                <p class="principal-cartao-venda-info-number">{{ $totalVendas }}</p>
            </div>
            <a href="{{ route('admin.vendas.index') }}" class="principal-cartao-venda-link">Ver Vendas</a>
        </div>

        <!-- Categorias -->
        <div class="principal-cartao-categoria">
            <div class="principal-cartao-categoria-info">
                <h3>Categorias</h3>
                <p class="principal-cartao-categoria-info-number">{{ $totalCategorias }}</p>
            </div>
            <a href="#" class="principal-cartao-categoria-link">Ver Categorias</a>
        </div>
    </div>
</div>