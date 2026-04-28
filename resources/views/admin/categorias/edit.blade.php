<link rel="stylesheet" href="{{ asset('css/produtos/app.css') }}">
<link rel="stylesheet" href="{{ asset('css/base.css') }}">

<div class="cabecalho-pagina">
    <h1>Editar Categoria</h1>
</div>

<div class="quadro-formulario">

    <form method="POST" action="{{ route('admin.categorias.atualizar', $categoria->id) }}">
        @csrf
        @method('PUT')

        <div class="coluna-campos">
            <label>Nome da Categoria</label>
            <input type="text" name="nome" value="{{ $categoria->nome }}" required>
        </div>

        <div class="botoes-rodape">
            <button type="submit" class="btn-confirmar">
                Atualizar
            </button>

            <a href="{{ route('admin.categorias.index') }}" class="btn-voltar">
                Voltar
            </a>
        </div>

    </form>

</div>