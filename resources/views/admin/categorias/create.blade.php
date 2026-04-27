<link rel="stylesheet" href="{{ asset('css/produtos/app.css') }}">
<link rel="stylesheet" href="{{ asset('css/base.css') }}">

<div class="cabecalho-pagina">
    <h1>Nova Categoria</h1>
</div>

<div class="quadro-formulario">

    <form method="POST" action="{{ route('admin.categorias.salvar') }}">
        @csrf

        <div class="coluna-campos">
            <label>Nome da Categoria</label>
            <input type="text" name="nome" placeholder="Digite o nome da categoria" required>
        </div>

        <div class="botoes-rodape">
            <button type="submit" class="btn-confirmar">
                Salvar
            </button>

            <a href="{{ route('admin.categorias.index') }}" class="btn-voltar">
                Voltar
            </a>
        </div>

    </form>

</div>
