<link rel="stylesheet" href="{{ asset('css/app.css') }}">

<div class="painel-controle">
    <h1>Histórico de Vendas</h1>

    <div class="area-ferramentas">
        <form class="filtro-busca" method="GET" action="{{ route('admin.vendas.index') }}">
            <input type="text" name="cliente" placeholder="Buscar cliente..." value="{{ request('cliente') }}">

            <input type="date" name="data_inicio" placeholder="Data início" value="{{ request('data_inicio') }}">
            <input type="date" name="data_fim" placeholder="Data fim" value="{{ request('data_fim') }}">

            <button type="submit" class="btn-buscar">Buscar</button>
        </form>
    </div>
</div>

<div class="listagem-estoque">

    <div class="grade-cabecalho">
        <span>#ID</span>
        <span>Cliente</span>
        <span>Data da Venda</span>
        <span>Qtd Produtos</span>
        <span>Total</span>
        <span>Ações</span>
    </div>

    @forelse ($vendas as $venda)
        <div class="item-linha">

            <!-- ID -->
            <div class="celula id">
                #{{ $venda->id }}
            </div>

            <!-- CLIENTE -->
            <div class="celula detalhes">
                <strong>{{ $venda->cliente->nome ?? 'Cliente não encontrado' }}</strong>
            </div>

            <!-- DATA -->
            <div class="celula grupo">
                {{ \Carbon\Carbon::parse($venda->data)->format('d/m/Y') }}
            </div>

            <!-- QTD PRODUTOS -->
            <div class="celula volume">
                {{ $venda->produtos->count() }} itens
            </div>

            <!-- TOTAL -->
            <div class="celula valor">
                R$ {{ number_format($venda->total, 2, ',', '.') }}
            </div>

            <!-- AÇÕES -->
            <div class="celula botoes">
                <a href="{{ route('admin.vendas.detalhes', $venda->id) }}" class="acao-edit">Ver Detalhes</a>
            </div>

        </div>
    @empty
        <div class="item-linha vazio">
            <p style="grid-column: 1 / -1; text-align: center; padding: 20px;">Nenhuma venda encontrada</p>
        </div>
    @endforelse

</div>