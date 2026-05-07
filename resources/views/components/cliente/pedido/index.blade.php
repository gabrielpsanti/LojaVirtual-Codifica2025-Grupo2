<link rel="stylesheet" href="{{ asset('css/carrinho.css') }}">

<div class="container">
    <!-- Barra de progresso -->
    <div class="container-estapas">
        <div class="container-estapas-carrinho">
            <p>1. Carrinho</p>
        </div>
        <div class="container-estapas-frete">
            <p>2. Frete</p>
        </div>
        <div class="container-estapas-pedido">
            <p>3. Pedido</p>
        </div>
        <div class="container-estapas-pagamento">
            <p>4. Pagamento</p>
        </div>
    </div>

    <!-- Dados de entrega -->
    <div class="container-input">
        <h3>Email</h3>
        <input type="email" class="input" placeholder="seu@email.com">

        <h3>CEP</h3>
        <input type="text" class="input" id="cep" placeholder="00000-000" maxlength="9">

        <h3>Rua</h3>
        <input type="text" class="input" id="logradouro" placeholder="Rua / Avenida">

        <h3>Número</h3>
        <input type="text" class="input" id="numero" placeholder="Numero">

        <h3>Bairro</h3>
        <input type="text" class="input" id="bairro" placeholder="Bairro">

        <h3>Cidade</h3>
        <input type="text" class="input" id="cidade" placeholder="Cidade">

        <h3>UF</h3>
        <input type="text" class="input" id="uf" placeholder="UF" maxlength="2">

    </div>

    <!-- Forma de Pagamento -->
    <div class="container-pagamentos">
        <div class="container-pagamentos-items">
            <h3>Formas de Pagamento</h3>
            <select name="pagamento" id="pagamento">
                <option value="">Selecione uma forma de pagamento</option>
                <option value="pix">PIX</option>
            </select>
        </div>
    </div>

    <!-- Resumo dos Produtos -->
    <div class="container-produtos">
        <h3>Itens do Pedido</h3>

        @forelse($carrinho as $item)
            <div class="container-item-carrinho">
                <div>
                    <h4>{{ $item['nome'] }}</h4>
                    <div style="margin-top: 5px;">
                        <span>Qtd: {{ $item['quantidade'] }} x R$ {{ number_format($item['preco'], 2, ',', '.') }}</span>
                        <strong style="margin-left: 10px;">R$ {{ number_format($item['subtotal'], 2, ',', '.') }}</strong>
                    </div>
                </div>

                <!-- Botão de Excluir -->
                <form action="{{ route('carrinho.remover', $item['id']) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="botao-excluir" title="Remover item">
                        <i>Excluir</i>
                    </button>
                </form>
            </div>
        @empty
            <p style="text-align: center; color: #636e72;">Seu carrinho está vazio.</p>
        @endforelse
    </div>

    <!-- Resumo de Valores -->
    <div class="resumo-total">
        <div style="display: flex; justify-content: space-between; margin-bottom: 10px;">
            <span>Subtotal</span>
            <span>R$ {{ number_format($total ?? 0, 2, ',', '.') }}</span>
        </div>
        <div style="display: flex; justify-content: space-between; margin-bottom: 10px;">
            <span>Frete</span>
            <span id="display-frete">A calcular</span>
        </div>
        <hr style="border: 0; border-top: 1px solid #eee; margin: 15px 0;">
        <div style="display: flex; justify-content: space-between; font-size: 1.3rem;">
            <strong>Total</strong>
            <strong style="color: #27ae60;" id="display-total">R$ {{ number_format($total ?? 0, 2, ',', '.') }}</strong>
        </div>
    </div>

    <form action="{{ route('checkout.finalizar') }}" method="POST" style="width: 100%; max-width: 600px;">
        @csrf
        <input type="hidden" name="total" id="input-total" value="{{ $total }}">
        <button type="submit" style="width: 100%;">Finalizar Compra</button>
    </form>
</div>

<script>
    document.getElementById('cep').addEventListener('input', async function () {
        // 1. Remove tudo que não é número
        let digits = this.value.replace(/\D/g, '');

        // 2. Aplica máscara no input
        this.value = digits.length > 5
            ? digits.slice(0, 5) + '-' + digits.slice(5, 8)
            : digits;

        // 3. Só busca com 8 dígitos puros (sem traço)
        if (digits.length !== 8) return;

        try {
            const res = await fetch(`https://viacep.com.br/ws/${digits}/json/`);
            const data = await res.json();

            if (data.erro) return;

            document.getElementById('logradouro').value = data.logradouro;
            document.getElementById('bairro').value = data.bairro;
            document.getElementById('cidade').value = data.localidade;
            document.getElementById('uf').value = data.uf;

            calcularFrete(data.uf);

        } catch (e) {
            console.error('Erro ao buscar CEP:', e);
        }
    });

    const valorSubtotal = {{ $total ?? 0 }};

    function calcularFrete(uf) {
        let valorFrete = 0;

        // Simulação básica de frete por região
        const regioes = {
            'SP': 15.00, 'RJ': 18.00, 'MG': 20.00, 'ES': 22.00, // Sudeste
            'PR': 25.00, 'SC': 28.00, 'RS': 30.00, // Sul
            'DF': 35.00, 'GO': 35.00, 'MT': 40.00, 'MS': 40.00, // Centro-Oeste
            'BA': 45.00, 'PE': 48.00, 'CE': 50.00, 'RN': 50.00, // Nordeste
        };

        valorFrete = regioes[uf] || 55.00; // Padrão para outras regiões

        const valorTotal = valorSubtotal + valorFrete;

        // Formatação em BRL
        const formatadorBRL = new Intl.NumberFormat('pt-BR', { style: 'currency', currency: 'BRL' });

        // Atualizar os elementos na tela
        document.getElementById('display-frete').innerText = formatadorBRL.format(valorFrete);
        document.getElementById('display-total').innerText = formatadorBRL.format(valorTotal);

        // Atualizar o input hidden para enviar ao backend
        document.getElementById('input-total').value = valorTotal;
    }
</script>