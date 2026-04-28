<ul id="navbar-list" class="w-auto no-underline flex items-center justify-around gap-8">
    <li class="navbar-item">
        <a href="{{ route('teste') }}" class="px-3 py-2 rounded-lg hover:bg-rosa-100 hover:text-azul-paleta">
            Início
        </a>
    </li>
    <li class="navbar-item">

        <div>
            <a id="botao-lista-suspensa" href="{{ route('teste') }}" class="px-3 py-2 rounded-lg flex gap-2 hover:bg-rosa-100 hover:text-azul-paleta">
                Produtos
                <span>
                    <svg class="size-4" fill="currentColor" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512"><!--!Font Awesome Free v7.2.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2026 Fonticons, Inc.--><path d="M32 288c-12.9 0-24.6 7.8-29.6 19.8S.2 333.5 9.4 342.6l160 160c12.5 12.5 32.8 12.5 45.3 0l160-160c9.2-9.2 11.9-22.9 6.9-34.9S364.9 288 352 288L32 288z"/></svg>
                </span>
            </a>
        </div>

        <div id="lista-suspensa" class="transition-all duration-300 ease-in-out invisible opacity-0 absolute w-full left-0 bg-rosa-paleta mt-8">
            <ul class="no-underline flex items-center justify-evenly gap-5 ">
                @foreach($categorias as $categoria)
                    <li class="p-4 flex justify-center w-full hover:bg-rosa-100">
                        <a href="" class=" hover:text-azul-paleta">
                            {{ $categoria->nome }}
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>

    </li>
    <li class="navbar-item ">
        <a href="{{ route('teste') }}" class="px-3 py-2 rounded-lg hover:bg-rosa-100 hover:text-azul-paleta">
            Contato
        </a>
    </li>
    <li class="navbar-item ">
        <a href="{{ route('teste') }}" class="px-3 py-2 rounded-lg hover:bg-rosa-100 hover:text-azul-paleta">
            Perguntas Frequentes
        </a>
    </li>
    <li class="navbar-item ">
        <a href="{{ route('teste') }}" class="px-3 py-2 rounded-lg hover:bg-rosa-100 hover:text-azul-paleta">
            Trocas e Devoluções
        </a>
    </li>
    <li class="navbar-item ">
        <a href="{{ route('teste') }}" class="px-3 py-2 rounded-lg hover:bg-rosa-100 hover:text-azul-paleta">
            Quem Somos
        </a>
    </li>
</ul>
<script>

    const mouseTarget = document.getElementById("botao-lista-suspensa");
    const listaSuspensa = document.getElementById("lista-suspensa");

    mouseTarget.addEventListener("mouseenter", (e) => {
        e.stopPropagation();
        listaSuspensa.classList.remove("invisible", "opacity-0");
        listaSuspensa.classList.add("visible", "opacity-100");
    });

    mouseTarget.addEventListener("mouseleave", (e) => {
        listaSuspensa.addEventListener("mouseleave", (e2) => {
            e.stopPropagation();
            listaSuspensa.classList.add("invisible", "opacity-0");
            listaSuspensa.classList.remove("visible", "opacity-100");
        })
    });

</script>

