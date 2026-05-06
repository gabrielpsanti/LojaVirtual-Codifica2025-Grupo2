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
            class="w-full bg-cinza-50 text-black border border-cinza-300 placeholder-gray-500 rounded-[10px] p-2 text-sm outline-none focus:ring-1 focus:ring-indigo-500"
        >

        <button type="submit" class="px-3 rounded-full hover:bg-rosa-100 transition-colors cursor-pointer" title="Buscar">
            🔍
        </button>
    </form>

</div>
