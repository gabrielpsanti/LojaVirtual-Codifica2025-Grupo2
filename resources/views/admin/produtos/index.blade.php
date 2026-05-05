<link rel="stylesheet" href="{{ asset('css/produtos/app.css') }}">
<link rel="stylesheet" href="{{ asset('css/base.css') }}">

<div class="painel-controle">
    <h1>Itens em Estoque</h1>

    <div class="area-ferramentas">
        <form class="filtro-busca" method="GET" action="{{ route('admin.produtos.index') }}">
            <input type="text" name="nome" placeholder="Buscar produto..." value="{{ request('nome') }}">

            <select name="categoria">
                <option value="">Todas as categorias</option>
                @foreach($categorias as $categoria)
                    <option value="{{ $categoria->id }}" {{ request('categoria') == $categoria->id ? 'selected' : '' }}>{{ $categoria->nome }}</option>
                @endforeach
            </select>

            <button type="submit" class="btn-buscar">Buscar</button>
        </form>

        <a href="{{ route('admin.produtos.criar') }}" class="btn-adicionar">
            Novo Item
        </a>
    </div>
</div>

<div class="listagem-estoque">

    <div class="grade-cabecalho">
        <span>Foto</span>
        <span>Nome do Produto</span>
        <span>Categoria</span>
        <span>Quantidade</span>
        <span>Preço</span>
        <span>Ações</span>
    </div>

    @foreach ($produtos as $produto)
        <div class="item-linha">

            <!-- FOTO -->
            <div class="celula miniatura">
                @if($produto->imagem)
                    <img src="{{ asset('storage/' . $produto->imagem) }}">
                @endif
            </div>

            <!-- NOME + DESC -->
            <div class="celula detalhes">
                <strong>{{ $produto->nome }}</strong>
                <p>{{ $produto->descricao }}</p>
            </div>

            <!-- CATEGORIA -->
            <div class="celula grupo">
                {{ $produto->categoria->nome ?? 'Sem categoria' }}
            </div>

            <!-- QTD -->
            <div class="celula volume">
                {{ $produto->quantidade }} un
            </div>

            <!-- PREÇO -->
            <div class="celula valor">
                R$ {{ number_format($produto->preco, 2, ',', '.') }}
            </div>

            <!-- AÇÕES -->
            <div class="celula botoes">
                <a href="{{ route('admin.produtos.editar', $produto->id) }}" class="acao-edit">Editar</a>

                <form action="{{ route('admin.produtos.deletar', $produto->id) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="acao-delete">Excluir</button>
                </form>
            </div>

        </div>
    @endforeach

</div>
