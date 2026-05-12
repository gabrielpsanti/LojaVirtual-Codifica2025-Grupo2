<div>

    <div class="grid grid-cols-[0.5fr_1fr]">
        <div class="grid grid-rows-[auto_0.5fr_05.fr] p-5 bg-amber-400">

            <h1>Minha Conta</h1>

            <div>
                <div class="grid grid-cols-2 p-2 bg-lilas-100">
                    <h2>Dados Pessoais</h2>
{{--                        <a href="{{ route() }}">Editar</a>--}}
                    <a>Editar</a>
                </div>
            </div>

            <div>
                <div class="grid grid-cols-2 p-2 bg-lilas-100">
                    <h2>Meus Endereços</h2>
{{--                        <a href="{{ route() }}">Editar</a>--}}
                    <a>Editar</a>
                </div>

            </div>

        </div>
        <div class="grid grid-rows-[auto_1fr]" >

            <h1>Meus pedidos</h1>

            <div class="grid grid-cols-2 gap-4">
                @foreach($pedidos as $pedido)
                    <div class="p-5">

                    </div>
{{--                @empty(count($pedidos) > 0)--}}


                @endforeach
            </div>

        </div>
    </div>

    <form action="{{ route('logout') }}" method="POST">
        <button type="submit">Finalizar Sessão</button>
    </form>

</div>
