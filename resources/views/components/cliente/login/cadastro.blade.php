<link rel="stylesheet" href="{{ asset('css/cadastro.css') }}">
<title>Cadastro</title>

<div class="fundo">
    <div class="quadro-formulario">
        <form class="estrutura-form" method="POST" action="{{ route('cadastro.salvar') }}">
            @csrf

            <div class="cabecalho-pagina">
                <h1>Faça seu Registro</h1>
            </div>

            <div class="coluna-campos">
                <label for="nome">Nome</label>
                <input type="text" name="nome" id="nome">

                <label for="email">E-mail</label>
                <input type="email" name="email" id="email">

                <label for="password">Senha</label>
                <div class="grid grid-cols-[1fr_auto] items-center gap-4">
                    <input type="password" name="password" id="password">
                    <span class="">
                        <button type="button" id="show-hide-password">
                            <span id="open-eye">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 576 512" class="size-6"><!--!Font Awesome Free v7.2.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2026 Fonticons, Inc.--><path d="M288 32c-80.8 0-145.5 36.8-192.6 80.6-46.8 43.5-78.1 95.4-93 131.1-3.3 7.9-3.3 16.7 0 24.6 14.9 35.7 46.2 87.7 93 131.1 47.1 43.7 111.8 80.6 192.6 80.6s145.5-36.8 192.6-80.6c46.8-43.5 78.1-95.4 93-131.1 3.3-7.9 3.3-16.7 0-24.6-14.9-35.7-46.2-87.7-93-131.1-47.1-43.7-111.8-80.6-192.6-80.6zM144 256a144 144 0 1 1 288 0 144 144 0 1 1 -288 0zm144-64c0 35.3-28.7 64-64 64-11.5 0-22.3-3-31.7-8.4-1 10.9-.1 22.1 2.9 33.2 13.7 51.2 66.4 81.6 117.6 67.9s81.6-66.4 67.9-117.6c-12.2-45.7-55.5-74.8-101.1-70.8 5.3 9.3 8.4 20.1 8.4 31.7z"/></svg>
                            </span>
                            <span id="closed-eye" hidden>
                                <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 576 512" class="size-6"><!--!Font Awesome Free v7.2.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2026 Fonticons, Inc.--><path d="M41-24.9c-9.4-9.4-24.6-9.4-33.9 0S-2.3-.3 7 9.1l528 528c9.4 9.4 24.6 9.4 33.9 0s9.4-24.6 0-33.9l-96.4-96.4c2.7-2.4 5.4-4.8 8-7.2 46.8-43.5 78.1-95.4 93-131.1 3.3-7.9 3.3-16.7 0-24.6-14.9-35.7-46.2-87.7-93-131.1-47.1-43.7-111.8-80.6-192.6-80.6-56.8 0-105.6 18.2-146 44.2L41-24.9zM204.5 138.7c23.5-16.8 52.4-26.7 83.5-26.7 79.5 0 144 64.5 144 144 0 31.1-9.9 59.9-26.7 83.5l-34.7-34.7c12.7-21.4 17-47.7 10.1-73.7-13.7-51.2-66.4-81.6-117.6-67.9-8.6 2.3-16.7 5.7-24 10l-34.7-34.7zM325.3 395.1c-11.9 3.2-24.4 4.9-37.3 4.9-79.5 0-144-64.5-144-144 0-12.9 1.7-25.4 4.9-37.3L69.4 139.2c-32.6 36.8-55 75.8-66.9 104.5-3.3 7.9-3.3 16.7 0 24.6 14.9 35.7 46.2 87.7 93 131.1 47.1 43.7 111.8 80.6 192.6 80.6 37.3 0 71.2-7.9 101.5-20.6l-64.2-64.2z"/></svg>
                            </span>
                        </button>
                    </span>
                </div>

                <label for="cpf_cnpj">CPF ou CNPJ</label>
                <input type="text" name="cpf_cnpj" id="cpf_cnpj" maxlength="14">
            </div>

            <div class="flex justify-center">
                <button id="login-submit-button" class="btn-confirmar" type="submit">Registrar</button>
            </div>
            <a href="{{ route('login') }}">Já possui uma conta?</a>
        </form>
    </div>
</div>
<script>

    let passwordToggleButton = document.getElementById('show-hide-password');

    passwordToggleButton.addEventListener('click', e => {
        let openEye = document.getElementById('open-eye');
        let closedEye = document.getElementById('closed-eye');
        let pwd = document.getElementById("password");

        if (pwd.type === "password") {
            pwd.type = "text";
            openEye.hidden = true;
            closedEye.hidden = false;
        } else {
            pwd.type = "password";
            openEye.hidden = false;
            closedEye.hidden = true;
        }
    })

    let submitPassaword = document.getElementById('login-submit-button');

    submitPassaword.addEventListener('click', e => {

        let pwd = document.getElementById("password");

        if (pwd.type !== "password") {
            pwd.type = "password";
        }
    })

</script>
