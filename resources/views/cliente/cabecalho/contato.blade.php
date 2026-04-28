<link rel="stylesheet" href="{{ asset('css/contatocliente.css') }}">
<div class="contato-container">

    <h1>Contato🧶</h1>

    @if(session('success'))
        <div class="contato-sucesso">
            {{ session('success') }}
        </div>
    @endif

    <form class="contato-form" method="POST" action="{{ route('cabecalho.contato.enviar') }}">
        @csrf

        <input type="text" name="nome" placeholder="Seu nome" required>

        <input type="email" name="email" placeholder="Seu e-mail" required>

        <textarea name="mensagem" placeholder="Sua mensagem" required></textarea>

        <button type="submit">Enviar mensagem</button>
    </form>

    <div class="contato-info">
        Respondemos em até 2 dias úteis!
    </div>

</div>