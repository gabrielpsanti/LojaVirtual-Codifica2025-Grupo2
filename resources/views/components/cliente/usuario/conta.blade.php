    <div>

        <div class="grid grid-cols-[0.5fr_1fr]">
            <div class="grid grid-rows-[auto_0.5fr_05.fr]">

                <h1>Minha Conta</h1>

                <div>
                    <div>
                        <h2>Dados Pessoais</h2>
                        <a href="{{ route() }}">Editar</a>
                    </div>
                </div>

                <div>
                    <div>
                        <h2>Meus Endereços</h2>
                        <a href="{{ route() }}">Editar</a>
                    </div>

                </div>

            </div>
            <div class="grid grid-rows-[auto_1fr]" >

                <h1>Meus pedidos</h1>

                <div class="grid grid-cols-2 gap-4">
                    @forelse($pedidos as $pedido)
                        <div class="">

                        </div>
                    @endforelse
                </div>

            </div>
        </div>

        <form action="{{ route('usuario.sair') }}" method="POST">
            <button type="submit">Finalizar Sessão</button>
        </form>

    </div>
</x-cliente.layout>
