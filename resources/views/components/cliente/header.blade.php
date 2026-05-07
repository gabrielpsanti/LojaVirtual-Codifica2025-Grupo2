<header id="scrolling-header" class="fixed bg-rosa-paleta px-6 py-3 text-indigo-500 border-b border-rosa-100 shadow-sm transition-all duration-500 ease-in">

    <div class="flex items-center justify-between gap-4">

        <div class="flex items-center gap-6 w-full max-w-2xl">

            <a href="{{ route('index') }}" class="">
                <img class="w-auto h-16 rounded-full hover:scale-105 transition-transform" src="{{ asset('assets/lojinha.png') }}" alt="Logo Lojinha">
            </a>

            <div class="flex">
                <x-cliente.search-bar />
            </div>

        </div>

        <div class="flex grow items-center justify-end gap-6">

            <x-cliente.navbar :categorias="$categorias"/>

            <div class="flex gap-4 text-xl">
                <a href="{{ route('usuario.index') }}" title="Minha Conta" class="hover:scale-110 transition-transform">👤</a>
                <a href="{{ route('checkout.carrinho.view') }}" title="Meu Carrinho" class="hover:scale-110 transition-transform">🛒</a>
            </div>

        </div>

    </div>
</header>

<script>

    (function() {
        var prevScrollpos = window.pageYOffset;
        window.onscroll = function() {
            var currentScrollPos = window.pageYOffset;
            if (prevScrollpos > currentScrollPos) {
                document.getElementById("scrolling-header").style.top = "0";
            } else {
                document.getElementById("scrolling-header").style.top = "-100px";
            }
            prevScrollpos = currentScrollPos;
        }
    })();

</script>
