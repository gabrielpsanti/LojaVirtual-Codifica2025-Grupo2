<link rel="stylesheet" href="{{ asset('css/showProdutoCliente.css') }}">

<div class="container-principal">
    <!-- Seção de fotos e opções de compra -->
    <div class="container-principal-produto">

        <!-- Galeria de Fotos -->
        <div class="container-principal-produto-fotos">
            <!-- Miniaturas-->
            <div class="container-principal-produto-fotos-miniatura">
                <div class="container-principal-produto-fotos-miniatura-1">
                    <img>
                </div>
                <div class="container-principal-produto-fotos-miniatura-2">
                    <img>
                </div>
                <div class="container-principal-produto-fotos-miniatura-3">
                    <img>
                </div>
                <div class="container-principal-produto-fotos-miniatura-4">
                    <img>
                </div>
            </div>

            <!-- Foto Principal Exibida -->
            <div class="container-principal-produto-fotos-tamanhoNormal">
                <img id="foto-principal" src="{{ asset('storage/' . $produto->imagem) }}" alt="{{ $produto->nome }}">
            </div>
        </div>

        <!-- Detalhes e Opções de Compra -->
        <div class="container-princiapal-produto-detalhes">
            <div class="container-princiapal-produto-detalhes-nomeProduto">
                {{ $produto->categoria->nome ?? 'Produto' }}
            </div>
            <h1>{{ $produto->nome }}</h1>

            <div class="container-princiapal-produto-detalhes-valores">
                <span class="container-princiapal-produto-detalhes-valores-cifrao">R$</span>
                <span
                    class="container-princiapal-produto-detalhes-valores-valor">{{ number_format($produto->preco, 2, ',', '.') }}</span>
            </div>

            <div class="container-princiapal-produto-detalhes-frete">
                <label for="cep"><i class="container-princiapal-produto-detalhes-fret-titulo"></i> Calcular
                    Frete</label>
                <div class="container-princiapal-produto-detalhes-frete-input">
                    <input type="text" id="cep" name="cep" placeholder="00000-000" maxlength="9"
                        oninput="mascaraCep(this)">
                    <button type="button" onclick="calcularFrete()">Calcular</button>
                </div>
                <div id="resultado-frete"></div>
            </div>

            <div class="container-princiapal-produto-detalhe-compra">
                <form action="{{ route('checkout.carrinho') }}" method="POST">
                    @csrf
                    <input type="hidden" name="produto_id" value="{{ $produto->id }}">
                    <button type="submit" class="container-princiapal-produto-detalhe-compra-adicionar">
                        <i class="container-princiapal-produto-detalhe-compra-adicionar-iconeCarrinho"></i>
                        Adicionar ao
                        Carrinho
                    </button>
                </form>
            </div>

            <div class="container-princiapal-produto-detalhes-informacoes">
                <div class="container-princiapal-produto-detalhes-informacoes-estoque">
                    <i class="container-princiapal-produto-detalhes-informacoes-estoque-iconeEstoque"></i>
                    <span>Estoque: <strong>{{ $produto->quantidade }}</strong> unidades</span>
                </div>
                <div class="container-princiapal-produto-detalhes-informacoes-infoGarantia">
                    <i class="container-princiapal-produto-detalhes-informacoes-infoGarantia-iconeGarantia"></i>
                    <span>Garantia de 30 dias</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Seção de Descrição -->
    <div class="container-principal-descricao">
        <div class="container-princiacal-descricao">
            <h2><i class="container-princiacal-descricao-titulo"></i> Descrição do Produto</h2>
            <div class="container-princiacal-descricao-titulo-texto">
                <p>{{ $produto->descricao }}</p>
            </div>
        </div>
    </div>

    <!-- Seção de Avaliações -->
    <div class="container-principal-avaliacoes">
        <div class="container-principal-comentario">
            <h2><i class="container-principal-titulo"></i> Avaliações</h2>
            <div class="container-principal-formularioComentario">
                <textarea placeholder="O que você achou deste produto?"></textarea>
                <button class="container-principal-formularioComentario-enviar">Enviar Comentário</button>
            </div>
        </div>

        <div class="container-principal-avaliacoes-avaliacoes">
            <div class="container-principal-avaliacoes-avaliacoes-item">
                <div class="container-principal-avaliacoes-avaliacoes-item-usuario">
                    <div class="container-principal-avaliacoes-avaliacoes-item-usuario-letra">CT</div>
                    <strong>Cliente Teste</strong>
                </div>
                <p>Ótimo produto, a qualidade me surpreendeu bastante. Recomendo!</p>
            </div>
        </div>
    </div>

    <!-- Seção de Produtos Relacionados -->
    <div class="container-principal-relacionados">
        <h2 class="container-principal-relacionados-titulo">Produtos Relacionados</h2>
        <div class="container-principal-relacionados-relacionados">
            <div class="container-principal-relacionados-relacionados-item">
                <img>
                <h3></h3>
                <p class="container-principal-relacionados-relacionados-item-preco"></p>
                <button class="container-principal-relacionados-relacionados-item-ver">Ver Produto</button>
            </div>
            <div class="container-principal-relacionados-relacionados-item">
                <img>
                <h3></h3>
                <p class=" container-principal-relacionados-relacionados-item-preco">
                </p>
                <button class="container-principal-relacionados-relacionados-item-ver"></button>

            </div>
        </div>
    </div>
</div>
<script>
    function trocarImagem(src, element) {
        document.getElementById('foto-principal').src = src;
        document.querySelectorAll('.wrapper-miniatura').forEach(tw => tw.classList.remove('ativo'));
        element.parentElement.classList.add('ativo');
    }

    // Máscara de CEP: 00000-000
    function mascaraCep(input) {
        let v = input.value.replace(/\D/g, '');
        if (v.length > 5) v = v.substring(0, 5) + '-' + v.substring(5, 8);
        input.value = v;
    }

    // Consulta ViaCEP e exibe opções de frete simuladas
    function calcularFrete() {
        const cepInput = document.getElementById('cep');
        const resultado = document.getElementById('resultado-frete');
        const cep = cepInput.value.replace(/\D/g, '');

        if (cep.length !== 8) {
            resultado.innerHTML = '<p class="frete-erro">Digite um CEP válido com 8 dígitos.</p>';
            return;
        }

        resultado.innerHTML = '<p class="frete-carregando">Consultando...</p>';

        fetch(`https://viacep.com.br/ws/${cep}/json/`)
            .then(res => res.json())
            .then(data => {
                if (data.erro) {
                    resultado.innerHTML = '<p class="frete-erro">CEP não encontrado. Verifique e tente novamente.</p>';
                    return;
                }

                // Simula valores de frete baseado na região
                const regiao = data.uf;
                const fretes = calcularValoresFrete(regiao);

                resultado.innerHTML = `
                    <p class="frete-endereco">
                        <strong>${data.localidade}</strong> - ${data.uf}
                    </p>
                    <div class="frete-opcoes">
                        <div class="frete-opcao">
                            <span class="frete-opcao-nome">PAC</span>
                            <span class="frete-opcao-prazo">${fretes.pac.prazo} dias úteis</span>
                            <span class="frete-opcao-valor">R$ ${fretes.pac.valor}</span>
                        </div>
                        <div class="frete-opcao">
                            <span class="frete-opcao-nome">SEDEX</span>
                            <span class="frete-opcao-prazo">${fretes.sedex.prazo} dias úteis</span>
                            <span class="frete-opcao-valor">R$ ${fretes.sedex.valor}</span>
                        </div>
                    </div>
                `;
            })
            .catch(() => {
                resultado.innerHTML = '<p class="frete-erro">Erro ao consultar o CEP. Tente novamente.</p>';
            });
    }

    // Simula valores de frete por região (UF)
    function calcularValoresFrete(uf) {
        const sudeste = ['SP', 'RJ', 'MG', 'ES'];
        const sul = ['PR', 'SC', 'RS'];

        if (sudeste.includes(uf)) {
            return {
                pac: { valor: '15,90', prazo: '5-8' },
                sedex: { valor: '27,90', prazo: '2-3' }
            };
        } else if (sul.includes(uf)) {
            return {
                pac: { valor: '19,90', prazo: '6-9' },
                sedex: { valor: '32,90', prazo: '3-4' }
            };
        } else {
            return {
                pac: { valor: '28,90', prazo: '8-12' },
                sedex: { valor: '45,90', prazo: '4-6' }
            };
        }
    }
</script>