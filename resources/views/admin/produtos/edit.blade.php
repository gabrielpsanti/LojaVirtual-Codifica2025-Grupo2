<h1>Editar Produto</h1>

<form method="POST" action="{{ route('admin.produtos.atualizar', $produto->id) }}">
    @csrf
    @method('PUT')

    <input type="text" name="nome" value="{{ $produto->nome }}"><br>
    <input type="number" name="preco" step="any" value="{{ $produto->preco }}"><br>
    <input type="text" name="categoria" value="{{ $produto->categoria }}"><br>
    <textarea name="descricao">{{ $produto->descricao }}</textarea><br>
    <input type="number" name="estoque" value="{{ $produto->estoque }}"><br>

    <button>Atualizar</button>
</form>
