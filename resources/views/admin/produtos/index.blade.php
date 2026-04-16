<link rel="stylesheet" href="{{ asset('css/app.css') }}">

<h1>Produtos</h1>

<div class="container">

    <a href="{{ route('admin.produtos.criar') }}" class="novo">Novo Produto</a>

    <div class="grid">

        @foreach ($produtos as $produto)
            <div class="card">

                @if($produto->imagem)
                    <img src="{{ asset('storage/' . $produto->imagem) }}">
                @endif

                <h3>{{ $produto->nome }}</h3>
                <p class="preco">R$ {{ $produto->preco }}</p>
                <p class="desc">{{ $produto->descricao }}</p>
                <p class="estoque">Estoque: {{ $produto->estoque }}</p>

                <div class="botoes">

                    <a href="{{ route('admin.produtos.editar', $produto->id) }}" class="btn-editar">
                        Editar
                    </a>

                    <form action="{{ route('admin.produtos.deletar', $produto->id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn-excluir">
                            Excluir
                        </button>
                    </form>

                </div>

            </div>
        @endforeach

    </div>

</div>