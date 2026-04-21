<header id="header" class="bg-rosa-paleta px-5 py-2 text-indigo-500">
    <div id="bloco-superior" class="flex items-center gap-6
{{--    bg-lilas-300--}}
    ">
        <span id="search-span" class="p-1 pl-8 hover:text-azul-paleta">
            <div id="search-div" class="">
                <svg class="size-6" fill="currentColor" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><!--!Font Awesome Free v7.2.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2026 Fonticons, Inc.--><path d="M416 208c0 45.9-14.9 88.3-40 122.7L502.6 457.4c12.5 12.5 12.5 32.8 0 45.3s-32.8 12.5-45.3 0L330.7 376C296.3 401.1 253.9 416 208 416 93.1 416 0 322.9 0 208S93.1 0 208 0 416 93.1 416 208zM208 352a144 144 0 1 0 0-288 144 144 0 1 0 0 288z"/></svg>
            </div>
        </span>
        <span id="logo-span" class="flex grow justify-center p-1 hover:text-azul-paleta">
            <div id="logo-div" class="w-35 h-auto">
                <img class="rounded-[45%]" src="{{ asset('assets/lojinha.png') }}" alt="Logo">
            </div>
        </span>
        <span id="navbar-span" class="p-1 pr-8">
            <div id="navbar" class="">
                <x-cliente.navbar/>
            </div>
        </span>
        <span id="user-span" class="p-1 pr-5 hover:text-azul-paleta">
            <div id="user-div" class="">
                <svg class="size-6" fill="currentColor" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><!--!Font Awesome Free v7.2.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2026 Fonticons, Inc.--><path d="M224 248a120 120 0 1 0 0-240 120 120 0 1 0 0 240zm-29.7 56C95.8 304 16 383.8 16 482.3 16 498.7 29.3 512 45.7 512l356.6 0c16.4 0 29.7-13.3 29.7-29.7 0-98.5-79.8-178.3-178.3-178.3l-59.4 0z"/></svg>
            </div>
        </span>
        <span id="cart-span" class="p-1 pr-8 hover:text-azul-paleta">
            <div id="cart-div" class="">
                <svg class="size-6" fill="currentColor" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 512"><!--!Font Awesome Free v7.2.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2026 Fonticons, Inc.--><path d="M24-16C10.7-16 0-5.3 0 8S10.7 32 24 32l45.3 0c3.9 0 7.2 2.8 7.9 6.6l52.1 286.3c6.2 34.2 36 59.1 70.8 59.1L456 384c13.3 0 24-10.7 24-24s-10.7-24-24-24l-255.9 0c-11.6 0-21.5-8.3-23.6-19.7l-5.1-28.3 303.6 0c30.8 0 57.2-21.9 62.9-52.2L568.9 69.9C572.6 50.2 557.5 32 537.4 32l-412.7 0-.4-2c-4.8-26.6-28-46-55.1-46L24-16zM208 512a48 48 0 1 0 0-96 48 48 0 1 0 0 96zm224 0a48 48 0 1 0 0-96 48 48 0 1 0 0 96z"/></svg>
            </div>
        </span>
    </div>
{{--    <div id="bloco-inferior" class="--}}
{{--    bg-lilas-100--}}
{{--    flex justify-center m-2">--}}
{{--        --}}
{{--    </div>--}}
</header>
