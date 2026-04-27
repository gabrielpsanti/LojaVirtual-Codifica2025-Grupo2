<link rel="stylesheet" href="{{ asset('css/cadastro.css') }}">
<title>Cadastro</title>
<div class="fundo">
    <div class="fundo-caixa">
        <form class="fundo-caixa-formulario" method="POST" action="{{ route('cadastro.salvar') }}">
            @csrf
            <h1>Faça seu Registro</h1>

            <label for="nome">Nome</label>
            <input type="text" name="nome" id="nome">

            <label for="email">E-mail</label>
            <input type="email" name="email" id="email">

            <label for="password">Senha</label>
            <input type="password" name="password" id="password">

            <label for="cpf_cnpj">CPF ou CNPJ</label>
            <input type="text" name="cpf_cnpj" id="cpf_cnpj" maxlength="14">
            <button>Registrar</button>
            <a href="{{ route('login') }}">Ja possui conta ?</a>
        </form>
    </div>
</div>