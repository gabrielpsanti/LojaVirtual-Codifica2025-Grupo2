<link rel="stylesheet" href="{{ asset('css/login.css') }}">
<title>Login</title>
<div class="fundo">
    <div class="fundo-caixa">
        <form class="fundo-caixa-formulario" method="POST" > 
                    @csrf
                    <label for="email">E-mail</label>
                    <input type="email" name="email" id="email" value="{{ old('email') }}">
                    
                    <label for="password">Senha</label>
                    <input type="password" name="password" id="password">
                    
                    @error('email')
                        <span class="fundo-caixa-formulario-error">{{ $message }}</span>
                    @enderror
                    <button>Entrar</button>
                    <a href="{{ route('cadastro') }}">Registrar</a>
        </form>
    <div>
</div>