<x-cliente.layout :categorias="$categorias ?? []">
    
    <div class="p-8">
        
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-azul-paleta">Nossos Produtos</h1>
            <p class="text-indigo-500 mt-2">Tudo feito à mão com muito carinho!</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            
            @forelse($produtos as $produto)
                <div class="bg-white border-2 border-rosa-100 rounded-lg p-4 flex flex-col hover:bg-rosa-50">
                    
                    <div class="mb-4">
                        <img src="{{ asset('storage/' . $produto->imagem) }}" alt="Foto do produto" class="w-full h-48 object-cover rounded">
                    </div>

                    <h3 class="text-lg font-bold text-indigo-500">{{ $produto->nome }}</h3>
                    <p class="text-sm text-gray-500 mt-1 mb-4">
                        {{ $produto->descricao }}
                    </p>

                    <div class="mt-auto flex justify-between items-center">
                        <span class="font-bold text-azul-paleta text-xl">
                            R$ {{ number_format($produto->preco, 2, ',', '.') }}
                        </span>
                        
                        <a href="{{ route('produtos.detalhes', $produto->id) }}" class="bg-rosa-paleta text-indigo-500 px-3 py-2 rounded font-bold hover:bg-rosa-100">
                            Detalhes
                        </a>
                    </div>
                </div>
            @empty
                <div class="col-span-4 text-center p-10 bg-rosa-100 rounded">
                    <p class="text-indigo-500 font-bold">Nenhum produto encontrado :(</p>
                    <a href="{{ route('index') }}" class="text-azul-paleta underline mt-2 inline-block">Limpar pesquisa</a>
                </div>
            @endforelse

        </div>
    </div>

</x-cliente.layout>