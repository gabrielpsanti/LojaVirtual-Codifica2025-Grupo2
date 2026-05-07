<link rel="stylesheet" href="{{ asset('css/carrinhoCliente.css') }}">

<div class="carrinho-container">
    <h1 class="carrinho-titulo">Meu Carrinho</h1>

    @if(count($carrinho) > 0)
        <div class="carrinho-conteudo">
            {{-- Lista de Itens --}}
            <div class="carrinho-itens">
                @foreach($carrinho as $item)
                    <div class="carrinho-item">
                        <div class="carrinho-item-imagem">
                            <img src="{{ asset('storage/' . $item['imagem']) }}" alt="{{ $item['nome'] }}">
                        </div>
                        <div class="carrinho-item-info">
                            <h3 class="carrinho-item-nome">{{ $item['nome'] }}</h3>
                            <p class="carrinho-item-descricao">{{ Str::limit($item['descricao'], 80) }}</p>
                            <div class="carrinho-item-quantidade">
                                <span>Qtd: {{ $item['quantidade'] }}</span>
                            </div>
                        </div>
                        <div class="carrinho-item-valores">
                            <span class="carrinho-item-preco-unitario">R$ {{ number_format($item['preco'], 2, ',', '.') }}
                                /un</span>
                            <span class="carrinho-item-subtotal">R$ {{ number_format($item['subtotal'], 2, ',', '.') }}</span>
                            <form action="{{ route('carrinho.remover', $item['id']) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="carrinho-item-remover">Remover</button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Resumo do Pedido --}}
            <div class="carrinho-resumo">
                <h2 class="carrinho-resumo-titulo">Resumo do Pedido</h2>

                <div class="carrinho-resumo-linha">
                    <span>Itens ({{ count($carrinho) }})</span>
                    <span>R$ {{ number_format($total, 2, ',', '.') }}</span>
                </div>
                <div class="carrinho-resumo-linha">
                    <span>Frete</span>
                    <span class="carrinho-resumo-frete">A calcular</span>
                </div>

                <div class="carrinho-resumo-divisor"></div>

                <div class="carrinho-resumo-total">
                    <strong>Total</strong>
                    <strong class="carrinho-resumo-total-valor">R$ {{ number_format($total, 2, ',', '.') }}</strong>
                </div>

                <a href="{{ route('checkout.carrinho.view') }}" class="carrinho-resumo-finalizar">
                    Finalizar Compra
                </a>

                <a href="{{ route('index') }}" class="carrinho-resumo-continuar">
                    Continuar Comprando
                </a>
            </div>
        </div>

    @else
        <div class="carrinho-vazio">
            <h2>Seu carrinho está vazio</h2>
            <p>Adicione produtos para começar suas compras!</p>
            <a href="{{ route('index') }}" class="carrinho-vazio-botao">Ver Produtos</a>
        </div>
    @endif
</div>