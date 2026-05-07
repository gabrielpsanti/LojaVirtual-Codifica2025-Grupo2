<style>
    @layer utilities {
        .no-scrollbar::-webkit-scrollbar {
            display: none;
        }
        .no-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
    }

    .scroll-infinito {
        animation: scroll-infinito 30s linear infinite;
    }

    @keyframes scroll-infinito {
        from {
            transform: translateX(0);
        }
        to {
            transform: translateX(-100%);
        }
    }

    #container:hover {
        overflow: auto;
    }

    #container:hover div {
        animation-play-state:paused;
    }

</style>
<div id="container" class="no-scrollbar w-full inline-flex flex-nowrap overflow-hidden h-fit py-1 bg-lilas-paleta text-azul-paleta text-sm">
    <div class="scroll-infinito flex [&_span]:mr-10 items-center whitespace-nowrap">
        <span>* * *</span>
        <span>
            Seja bem-vindo à nossa Lojinha! Somos uma loja digital de produtos de crochê feitos artesanalmente, incluindo pedidos personalizados S2
        </span>
        <span>* * *</span>
        <span>
            Para fazer seu pedido personalizado entre em contato pela aba de "Contato" ou peça algo especial do nosso catálogo! <3
        </span>
        <span>* * *</span>
        <span>
            Utilize o cupom <strong>PRIMEIRA5OFF</strong> para ganhar 5% de desconto na primeira compra! :)
        </span>
    </div>
    <div class="scroll-infinito flex gap-10 items-center whitespace-nowrap">
        <span>* * *</span>
        <span>
            Seja bem-vindo à nossa Lojinha! Somos uma loja digital de produtos de crochê feitos artesanalmente, incluindo pedidos personalizados S2
        </span>
        <span>* * *</span>
        <span>
            Para fazer seu pedido personalizado entre em contato pela aba de "Contato" ou peça algo especial do nosso catálogo! <3
        </span>
        <span>* * *</span>
        <span>
            Utilize o cupom <strong>PRIMEIRA5OFF</strong> para ganhar 5% de desconto na primeira compra! :)
        </span>
    </div>

</div>

