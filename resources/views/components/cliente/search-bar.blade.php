<div class="w-full max-w-sm">
    
    <form action="{{ route('index') }}" method="GET" class="flex gap-1">
        @if(request('categoria'))
            <input type="hidden" name="categoria" value="{{ request('categoria') }}">
        @endif

        <input 
            type="text" 
            name="nome" 
            value="{{ request('nome') }}" 
            placeholder="Buscar produtos..." 
            class="w-full bg-white text-black border border-black placeholder-gray-500 rounded p-2 text-sm outline-none focus:ring-1 focus:ring-black"
        >
        
        <button type="submit" class="bg-white border border-black px-3 rounded hover:bg-gray-100 transition-colors cursor-pointer" title="Buscar">
            🔍
        </button>
    </form>
    
</div>