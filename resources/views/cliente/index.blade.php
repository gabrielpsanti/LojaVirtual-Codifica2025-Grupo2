<x-cliente.layout :categorias="$categorias ?? []">
    <x-cliente.pagina-inicial.scroll-automatico/>

    <x-cliente.pagina-inicial.recentes :produtosRecentes="$produtosRecentes ?? []"/>

    <x-cliente.pagina-inicial.promocoes :produtosPromo="$produtosPromo ?? []"/>
</x-cliente.layout>
