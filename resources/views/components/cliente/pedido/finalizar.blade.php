<link rel="stylesheet" href="{{ asset('css/finalizar.css') }}">

<div class="container">
    <h2>Pedido Recebido com Sucesso!</h2>
    <p>Agora é só realizar o pagamento via PIX para processarmos seu envio.</p>

    <div class="container-total">
        Total: R$ {{ number_format($total, 2, ',', '.') }}
    </div>

    <div class="container-qrcode">
        <p>Escaneie o QR Code abaixo:</p>
        <img src="https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=00020101021226850014BR.GOV.BCB.PIX0136{{ md5(time()) }}5204000053039865405{{ $total }}5802BR5913LOJA_VIRTUAL6009SAO_PAULO62070503***6304"
            alt="QR Code PIX" class="container-qrcode-imagem">

        <p class="container-qrcode-p">Ou copie o código abaixo:</p>
        <div class="container-qrcode-pix" id="pixCode">
            00020101021226850014BR.GOV.BCB.PIX0136{{ md5(time()) }}5204000053039865405{{ $total }}5802BR5913LOJA_VIRTUAL6009SAO_PAULO62070503***6304
        </div>
        <button class="container-qrcode-botaoCopiar" onclick="copiarPix()">Copiar Código PIX</button>
    </div>

    <br>
    <a href="{{ route('index') }}" class="container-botaoVoltar">Voltar para a Loja</a>
</div>

<script src="{{ asset('js/finalizar.js') }}"></script>