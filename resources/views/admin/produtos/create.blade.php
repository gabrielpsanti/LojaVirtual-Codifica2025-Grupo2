<h1>Criar Produto</h1>

<form method="POST" action="{{ route('admin.produtos.inserir') }}" enctype="multipart/form-data">
    @csrf

    <input type="text" name="nome" placeholder="Nome"><br><br>
    <input type="number" name="preco" step="0.01" placeholder="Preço"><br><br>
    <input type="text" name="categoria" placeholder="Categoria"><br><br>
    <textarea name="descricao" placeholder="Descrição"></textarea><br><br>
    <input type="number" name="estoque" placeholder="Estoque"><br><br>
    <input type="file" name="imagem"><br><br>

    <button type="submit">Salvar</button>
</form>