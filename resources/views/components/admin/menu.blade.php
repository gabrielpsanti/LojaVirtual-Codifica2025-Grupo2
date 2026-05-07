<div class="fixed w-fit h-screen left-0 p-5 bg-rosa-paleta">
    <div class="w-36 grid grid-cols-1 gap-6 justify-center">
        <div id="logo-div">
            <div id="logo-div" class="h-auto">
                <img class="rounded-full hover:scale-105 transition-transform" src="{{ asset('assets/lojinha.png') }}" alt="Logo">
            </div>
        </div>
        <div id="list-div">
            <ul class="grid grid-cols-1 gap-6 no-underline text-white">
                <li class="">
                    <div id="dashboard-div" >
                        <a href="{{ route('admin.dashboard') }}" class="w-full flex p-2 rounded-2xl text-md bg-verde-paleta hover:bg-verde-700">
                            Dashboard
                        </a>
                    </div>
                </li>
                <li class="">
                    <div id="estoque-div" >
                        <button class="w-full flex justify-between gap-2 p-2 rounded-2xl text-md bg-verde-paleta hover:bg-verde-700">
                            Estoque
                            <span>
                                <svg class="size-4" fill="currentColor" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512"><!--!Font Awesome Free v7.2.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2026 Fonticons, Inc.--><path d="M32 288c-12.9 0-24.6 7.8-29.6 19.8S.2 333.5 9.4 342.6l160 160c12.5 12.5 32.8 12.5 45.3 0l160-160c9.2-9.2 11.9-22.9 6.9-34.9S364.9 288 352 288L32 288z"/></svg>
                            </span>
                        </button>
                        <div class="w-36 grid grid-cols-1 rounded-b-md absolute transition-all duration-300 ease-in-out invisible opacity-0 bg-verde-400">
                            <a href="{{ route('admin.produtos.index') }}" class="py-1 pl-2 hover:bg-verde-700">
                                Ver estoque
                            </a>
                            <a href="{{ route('admin.produtos.criar') }}" class="py-1 pl-2 hover:bg-verde-700 hover:rounded-b-md">
                                Novo produto
                            </a>
                        </div>
                    </div>
                </li>
                <li class="">
                    <div id="categorias-div" >
                        <button class="w-full flex justify-between gap-2 p-2 rounded-2xl text-md bg-verde-paleta hover:bg-verde-700">
                            Categorias
                            <span>
                                <svg class="size-4" fill="currentColor" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512"><!--!Font Awesome Free v7.2.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2026 Fonticons, Inc.--><path d="M32 288c-12.9 0-24.6 7.8-29.6 19.8S.2 333.5 9.4 342.6l160 160c12.5 12.5 32.8 12.5 45.3 0l160-160c9.2-9.2 11.9-22.9 6.9-34.9S364.9 288 352 288L32 288z"/></svg>
                            </span>
                        </button>
                        <div class="w-36 grid grid-cols-1 rounded-b-md absolute transition-all duration-300 ease-in-out invisible opacity-0 bg-verde-400">
                            <a href="{{ route('admin.categorias.index') }}" class="py-1 pl-2 hover:bg-verde-700">
                                Ver categorias
                            </a>
                            <a href="{{ route('admin.categorias.criar') }}" class="py-1 pl-2 hover:bg-verde-700 hover:rounded-b-md">
                                Nova categoria
                            </a>
                        </div>
                    </div>
                </li>
                <li class="">
                    <div id="vendas-div" >
                        <button class="w-full flex justify-between gap-2 p-2 rounded-2xl text-md bg-verde-paleta hover:bg-verde-700">
                            Vendas
                            <span>
                                <svg class="size-4" fill="currentColor" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512"><!--!Font Awesome Free v7.2.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2026 Fonticons, Inc.--><path d="M32 288c-12.9 0-24.6 7.8-29.6 19.8S.2 333.5 9.4 342.6l160 160c12.5 12.5 32.8 12.5 45.3 0l160-160c9.2-9.2 11.9-22.9 6.9-34.9S364.9 288 352 288L32 288z"/></svg>
                            </span>
                        </button>
                        <div class="w-36 grid grid-cols-1 rounded-b-md absolute transition-all duration-300 ease-in-out invisible opacity-0 bg-verde-400">
                            <a href="{{ route('admin.vendas.index') }}" class="py-1 pl-2 hover:bg-verde-700">
                                Ver vendas
                            </a>
                            <a href="{{ route('admin.vendas.criar') }}" class="py-1 pl-2 hover:bg-verde-700 hover:rounded-b-md">
                                Nova venda
                            </a>
                        </div>
                    </div>
                </li>
                <li class="">
                    <div id="descontos-div" >
                        <button class="w-full flex justify-between gap-2 p-2 rounded-2xl text-md bg-verde-paleta hover:bg-verde-700">
                            Descontos
                            <span>
                                <svg class="size-4" fill="currentColor" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512"><!--!Font Awesome Free v7.2.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2026 Fonticons, Inc.--><path d="M32 288c-12.9 0-24.6 7.8-29.6 19.8S.2 333.5 9.4 342.6l160 160c12.5 12.5 32.8 12.5 45.3 0l160-160c9.2-9.2 11.9-22.9 6.9-34.9S364.9 288 352 288L32 288z"/></svg>
                            </span>
                        </button>
                        <div class="w-36 grid grid-cols-1 rounded-b-md absolute transition-all duration-300 ease-in-out invisible opacity-0 bg-verde-400">
                            <a href="{{ route('admin.descontos.index') }}" class="py-1 pl-2 hover:bg-verde-700">
                                Ver descontos
                            </a>
                            <a href="{{ route('admin.descontos.criar') }}" class="py-1 pl-2 hover:bg-verde-700 hover:rounded-b-md">
                                Novo desconto
                            </a>
                        </div>
                    </div>
                </li>
                <li class="">
                    <div id="enderecos-div">
                        <a href="{{ route('admin.usuarios') }}" class="w-full flex p-2 rounded-2xl text-md bg-verde-paleta hover:bg-verde-700">
                            Endereços
                        </a>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</div>
<script>

    const botoes = document.getElementsByTagName("button");

    for (let i = 0; i < botoes.length; i++){
        botoes[i].onclick = mostrarMenuSuspenso;
    }

    function mostrarMenuSuspenso(e) {
        const divLista = e.currentTarget.nextElementSibling;

        if (divLista.classList.contains("visible")) {
            divLista.classList.add("invisible", "opacity-0", "absolute");
            divLista.classList.remove("visible", "opacity-100");

        } else {
            const dropdownAtivo = document.getElementsByClassName("visible");

            if (dropdownAtivo.length > 0) {
                dropdownAtivo[0].classList.add("invisible", "opacity-0", "absolute");
                dropdownAtivo[0].classList.remove("visible", "opacity-100");
            }

            divLista.classList.remove("invisible", "opacity-0", "absolute");
            divLista.classList.add("visible", "opacity-100");
        }
    }

</script>
