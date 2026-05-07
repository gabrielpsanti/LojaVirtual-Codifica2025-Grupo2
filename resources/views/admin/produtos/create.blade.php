<x-admin.layout>
<link rel="stylesheet" href="{{ asset('css/admin/app.css') }}">
<div class="cabecalho-pagina">
    <h1>Cadastro de Produto</h1>
</div>

<div class="navegacao-abas">
    <a href="{{ route('admin.produtos.index') }}">Listagem</a>
</div>

<div class="quadro-formulario">

    <h2>Novo Cadastro</h2>

    <form method="POST" action="{{ route('admin.produtos.salvar') }}" enctype="multipart/form-data">
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
                <label for="imagens">
                    <div class="zona-preview" id="preview-container">
                        Clique para selecionar imagens
                    </div>
                </label>
                <input type="file" name="imagens[]" id="imagens" multiple accept="image/*">
                <div id="imagens-selecionadas" class="imagens-preview-container"></div>
            </div>

            <div class="coluna-campos">

                <label>Nome</label>
                <input type="text" name="nome" value="{{ old('nome') }}">

                <label>Categoria</label>
                <select name="categoria">
                    <option value="">Selecione uma categoria</option>
                    @foreach($categorias as $categoria)
                        <option value="{{ $categoria->id }}" {{ old('categoria') == $categoria->id ? 'selected' : '' }}>
                            {{ $categoria->nome }}
                        </option>
                    //comentar
                <select name="categoria_id">
                    <option value="">Selecione uma categoria</option>
                    @foreach($categorias as $categoria)
                        <option value="{{ $categoria->id }}" {{ old('categoria_id') == $categoria->id ? 'selected' : '' }}>{{ $categoria->nome }}</option>
                    //comentar
                    @endforeach
                </select>

                <label>Descrição</label>
                <textarea name="descricao">{{ old('descricao') }}</textarea>

                <div class="dupla-coluna">
                    <div>
                        <label>Estoque</label>
                        <input type="number" name="quantidade" min="0" value="{{ old('quantidade') }}">
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
<script>
    document.getElementById('imagens').addEventListener('change', function() {
        const container = document.getElementById('imagens-selecionadas');
        container.innerHTML = '';

        if (this.files && this.files.length > 0) {
            Array.from(this.files).forEach((file, index) => {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const div = document.createElement('div');
                    div.className = 'imagem-preview-item';
                    div.innerHTML = `
                        <div class="imagem-wrapper">
                            <img src="${e.target.result}" class="imagem-carregada-thumbnail" alt="Preview ${index + 1}">
                        </div>
                    `;
                    container.appendChild(div);
                };
                reader.readAsDataURL(file);
            });
        }
    });
</script>
</x-admin.layout>
