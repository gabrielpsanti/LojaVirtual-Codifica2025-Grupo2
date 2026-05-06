function copiarPix() {
    const pixElement = document.getElementById("pixCode");
    const texto = pixElement.innerText;

    const botao = document.querySelector(".container-qrcode-botaoCopiar");

    navigator.clipboard.writeText(texto).then(() => {

        // muda texto do botão
        botao.textContent = "✔ Copiado!";
        botao.classList.add("copiado");

        // volta ao normal depois de 2s
        setTimeout(() => {
            botao.textContent = "Copiar Código PIX";
            botao.classList.remove("copiado");
        }, 2000);

    }).catch(() => {
        alert("Erro ao copiar o código PIX");
    });
}