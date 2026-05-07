<x-admin.layout>
<link rel="stylesheet" href="{{ asset('css/admin/app.css') }}">

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
                @php
                    $imagemPrincipal = $produto->imagens->first();
                @endphp

                <label for="imagens">
                    <div class="zona-preview">
                        @if($imagemPrincipal)
                            <img src="{{ asset('storage/' . $imagemPrincipal->caminho) }}" class="imagem-carregada" alt="Imagem principal do produto">
                        @else
                            Clique para alterar imagem
                        @endif
                    </div>
                </label>
                <input type="file" name="imagens[]" id="imagens" multiple accept="image/*">

                <div id="imagens-selecionadas" class="imagens-preview-container">
                    @foreach($produto->imagens as $imagem)
                        <div class="imagem-preview-item">
                            <div class="imagem-wrapper">
                                <img src="{{ asset('storage/' . $imagem->caminho) }}" class="imagem-carregada-thumbnail" alt="Imagem do produto">
                                <button type="submit" form="deletar-imagem-{{ $imagem->id }}" class="btn-remover-imagem" onclick="return confirm('Tem certeza?')">X</button>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="coluna-campos">

                <label>Nome</label>
                <input type="text" name="nome" value="{{ $produto->nome }}">

                <label>Categoria</label>
                <select name="categoria">
                    <option value="">Selecione uma categoria</option>
                    @foreach($categorias as $categoria)
                        <option value="{{ $categoria->id }}" {{ $produto->categoria_id == $categoria->id ? 'selected' : '' }}>
                            {{ $categoria->nome }}
                        </option>
{{--                <select name="categoria_id">--}}
{{--                    <option value="">Selecione uma categoria</option>--}}
{{--                    @foreach($categorias as $categoria)--}}
{{--                        <option value="{{ $categoria->id }}" {{ $produto->categoria_id == $categoria->id ? 'selected' : '' }}>{{ $categoria->nome }}</option>--}}
                    @endforeach
                </select>

                <label>Descrição</label>
                <textarea name="descricao">{{ $produto->descricao }}</textarea>

                <div class="dupla-coluna">
                    <div>
                        <label>Estoque</label>
                        <input type="number" name="quantidade" min="0" value="{{ $produto->quantidade }}">
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

    @foreach($produto->imagens as $imagem)
        <form id="deletar-imagem-{{ $imagem->id }}" method="POST" action="{{ route('admin.produtos.deletarImagem', $imagem->id) }}">
            @csrf
            @method('DELETE')
        </form>
    @endforeach

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
<script>
    document.getElementById('imagens').addEventListener('change', function() {
        const container = document.getElementById('imagens-selecionadas');

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
