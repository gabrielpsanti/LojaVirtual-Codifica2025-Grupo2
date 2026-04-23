<link rel="stylesheet" href="{{ asset('css/produtos/app.css') }}">

<div class="cabecalho-pagina">
    <h1>Cadastro de Produto</h1>
</div>

<div class="navegacao-abas">
    <a href="{{ route('admin.produtos.index') }}">Listagem</a>
</div>

<div class="quadro-formulario">

    <h2>Novo Cadastro</h2>

    <form method="POST" action="{{ route('admin.produtos.inserir') }}" enctype="multipart/form-data">
        @csrf

        @if ($errors->any())
            <div class="alert-errors">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="estrutura-form">

            <div class="caixa-anexo">
                <label for="imagem">
                    <div class="zona-preview">
                        <br>Clique para selecionar uma imagem
                    </div>
                </label>
                <input type="file" name="imagem" id="imagem">
            </div>

            <div class="coluna-campos">

                <label>Nome</label>
                <input type="text" name="nome" value="{{ old('nome') }}">

                <label>Categoria</label>
                <input type="text" name="categoria" value="{{ old('categoria') }}">

                <label>Descrição</label>
                <textarea name="descricao">{{ old('descricao') }}</textarea>

                <div class="dupla-coluna">
                    <div>
                        <label>Estoque</label>
                        <input type="number" name="estoque" min="0" value="{{ old('estoque') }}">
                    </div>

                    <div>
                        <label>Preço</label>
                        <input type="number" step="0.01" min="0" name="preco" value="{{ old('preco') }}">
                    </div>
                </div>

                <div class="botoes-rodape">
                    <button class="btn-confirmar">Salvar Produto</button>
                    <a href="{{ route('admin.produtos.index') }}" class="btn-voltar">Cancelar</a>
                </div>

            </div>

        </div>

    </form>

</div>

<script>
    // Seletores atualizados para o novo padrão de nomes
    document.querySelectorAll('.caixa-anexo input[type="file"]').forEach(function(input) {
        input.addEventListener('change', function() {
            const area = this.closest('.caixa-anexo').querySelector('.zona-preview');
            if (this.files && this.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    area.innerHTML = '';
                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.className = 'imagem-carregada';
                    area.appendChild(img);
                };
                reader.readAsDataURL(this.files[0]);
            }
        });
    });
</script>
