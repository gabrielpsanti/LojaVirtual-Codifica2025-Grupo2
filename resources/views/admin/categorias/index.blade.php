<link rel="stylesheet" href="{{ asset('css/produtos/categoria.css') }}">
<link rel="stylesheet" href="{{ asset('css/base.css') }}">


<div class="painel-controle">
    <h1>Categorias</h1>

    <div class="area-ferramentas">
        <form class="filtro-busca" method="GET" action="{{ route('admin.categorias.index') }}">
            <input type="text" name="nome" placeholder="Buscar categoria..." value="{{ request('nome') }}">
            <button type="submit" class="btn-buscar">Buscar</button>
        </form>

        <a href="{{ route('admin.categorias.criar') }}" class="btn-adicionar">
            Nova Categoria
        </a>
    </div>
</div>

<div class="listagem-estoque categorias-page">

    <!-- CABEÇALHO -->
    <div class="grade-cabecalho">
        <span>Nome da Categoria</span>
        <span>Ações</span>
    </div>

    @foreach ($categorias as $categoria)
        <div class="item-linha">

            <!-- NOME -->
            <div class="detalhes">
                <strong>{{ $categoria->nome }}</strong>
                <p>Categoria cadastrada</p>
            </div>

            <!-- AÇÕES -->
            <div class="botoes">
                <a href="{{ route('admin.categorias.editar', $categoria->id) }}" class="acao-edit">
                    Editar
                </a>

                <form action="{{ route('admin.categorias.deletar', $categoria->id) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="acao-delete">
                        Excluir
                    </button>
                </form>
            </div>

        </div>
    @endforeach

</div>