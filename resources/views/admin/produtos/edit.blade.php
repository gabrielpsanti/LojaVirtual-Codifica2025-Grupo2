<link rel="stylesheet" href="{{ asset('css/produtos/app.css') }}">

<div class="cabecalho-pagina">
    <h1>Alterar Produto</h1>
</div>

<div class="navegacao-abas">
    <a href="{{ route('admin.produtos.index') }}">Listagem</a>
</div>

<div class="quadro-formulario">

    <h2>Editar Produto</h2>

    <form method="POST" action="{{ route('admin.produtos.atualizar', $produto->id) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="estrutura-form">

            <div class="caixa-anexo">
                <label for="imagem">
                    <div class="zona-preview">
                        @if($produto->imagem)
                            <img src="{{ asset('storage/' . $produto->imagem) }}" class="imagem-carregada">
                        @else
                            📷<br>Clique para alterar
                        @endif
                    </div>
                </label>
                <input type="file" name="imagem" id="imagem">
            </div>

            <div class="coluna-campos">

                <label>Nome</label>
                <input type="text" name="nome" value="{{ $produto->nome }}">

                <label>Categoria</label>
                <input type="text" name="categoria" value="{{ $produto->categoria }}">

                <label>Descrição</label>
                <textarea name="descricao">{{ $produto->descricao }}</textarea>

                <div class="dupla-coluna">
                    <div>
                        <label>Estoque</label>
                        <input type="number" name="estoque" value="{{ $produto->estoque }}">
                    </div>

                    <div>
                        <label>Preço</label>
                        <input type="number" step="0.01" name="preco" value="{{ $produto->preco }}">
                    </div>
                </div>

                <div class="botoes-rodape">
                    <button class="btn-confirmar">Atualizar Produto</button>
                    <a href="{{ route('admin.produtos.index') }}" class="btn-voltar">Cancelar</a>
                </div>

            </div>

        </div>

    </form>

</div>

<script>
    // Ajustei os seletores no JS para bater com as novas classes
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
