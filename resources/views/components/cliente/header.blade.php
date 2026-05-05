<header class="bg-rosa-paleta px-6 py-3 text-indigo-500 border-b border-rosa-100 shadow-sm">
    
    <div class="flex items-center justify-between gap-4">
        
        <div class="flex items-center gap-6 w-full max-w-2xl">
            
            <a href="{{ route('index') }}" class="flex-shrink-0">
                <img class="w-14 h-14 rounded-full hover:scale-105 transition-transform" src="{{ asset('assets/lojinha.png') }}" alt="Logo Lojinha">
            </a>

            <div class="flex-grow">
                <x-cliente.search-bar />
            </div>
            
        </div>

        <div class="flex items-center justify-end gap-6">
            
            <x-cliente.navbar :categorias="$categorias"/>
            
            <div class="flex gap-4 text-xl">
                <a href="{{ route('usuario.index') }}" title="Minha Conta" class="hover:scale-110 transition-transform">👤</a>
                <a href="{{ route('checkout.carrinho.view') }}" title="Meu Carrinho" class="hover:scale-110 transition-transform">🛒</a>
            </div>
            
        </div>
        
    </div>
</header>