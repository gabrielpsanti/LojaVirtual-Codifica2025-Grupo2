# Página "Meus Pedidos" do cliente — design

**Data:** 2026-05-12
**Escopo:** uma feature do front cliente + uma migration de schema + formalização da paleta visual do projeto.

## Problema

O cliente logado não tem como consultar os pedidos que já fez. A view `/conta` (`UsuarioController@index`) já carrega `$pedidos`, mas o `@foreach` no componente `resources/views/components/cliente/usuario/conta.blade.php` está vazio — não há linhas, cards ou link de detalhe.

Decisão: criar uma página dedicada `/conta/pedidos` (não preencher o `@foreach` da `/conta`). Motivo: a página de conta vai crescer (dados, endereços, pedidos) e o histórico de pedidos merece tela própria com sub-rota e detalhe.

## Paleta oficial do projeto

A paleta já está definida em `resources/css/app.css` (5 cores com escala 50–950 cada). Este spec **formaliza o uso** — todo Blade novo neste projeto deve seguir estas regras, e o `bg-amber-400` órfão em `components/cliente/usuario/conta.blade.php` será removido como cleanup oportunista.

| Token Tailwind | Hex | Papel |
|---|---|---|
| `rosa-paleta` | `#fac8cd` | Header da loja, CTAs primários, badges suaves |
| `lilas-paleta` | `#d7bcc8` | Sidebar "Minha Conta", superfícies secundárias |
| `cinza-paleta` | `#98b6b1` | Texto secundário, bordas, status `pendente` |
| `verde-paleta` | `#629677` | Sucesso, item ativo na navegação, status `entregue` |
| `azul-paleta` | `#495d63` | Texto principal, valores monetários, ênfase |

**Proibido daqui pra frente** em novo código: `bg-amber-*`, `bg-indigo-*`, `bg-yellow-*`, `bg-orange-*` e quaisquer cores default do Tailwind para superfícies, fundos ou ações. Exceção: pode usar `text-white`, `bg-white`, `text-gray-*` neutros para tipografia onde a paleta não cobre bem.

Para hover/foco/disabled, usar variações 50/100/200/etc da própria cor brand (ex.: hover do botão `bg-rosa-paleta` vira `hover:bg-rosa-100`).

## Status do pedido (novo campo de schema)

Hoje a tabela `vendas` não tem coluna de status. Adicionar uma migration `add_status_to_vendas_table`:

```php
$table->string('status', 20)->default('pendente')->after('preco_total_com_desconto');
$table->index('status');
```

**Valores aceitos** (sem enum nativo MySQL — string + constante PHP, mais flexível):

| Valor | Quando | Badge |
|---|---|---|
| `pendente` | Default ao criar venda | `cinza-100` bg, `azul-paleta` text |
| `pago` | Após cliente finalizar `/checkout/finalizar` | `azul-100` bg, `azul-700` text |
| `enviado` | Setado pelo admin em `/admin/vendas` | `lilas-100` bg, `azul-paleta` text |
| `entregue` | Setado pelo admin | `verde-100` bg, `verde-700` text |
| `cancelado` | Setado pelo admin OU pelo cliente (fora de escopo agora) | `rosa-50` bg, `#8a3a3a` text |

Listar as constantes em `App\Models\Venda` como statics (`Venda::STATUS_PENDENTE`, etc.) para evitar string mágica.

Mudanças associadas:
- `CompraController::finalizar` passa a setar `status = 'pago'` ao gravar a venda.
- Admin (`Admin\VendaController`) ganha select de status no edit (fora de escopo deste spec, mas a coluna fica disponível).

## Rota e controller

Adicionar em `routes/web.php`, dentro do grupo `middleware('auth')`:

```php
Route::get('/conta/pedidos',     [UsuarioController::class, 'pedidos'])->name('usuario.pedidos');
Route::get('/conta/pedidos/{id}', [UsuarioController::class, 'pedidoDetalhe'])->name('usuario.pedidos.detalhe');
```

Por que duas rotas: o layout é master-detail (lista + detalhe lado a lado no desktop), mas precisamos de URLs distintas para:
- compartilhamento e bookmark de um pedido específico
- comportamento sensato no mobile (uma rota = lista, outra = detalhe em página cheia)
- back-button do navegador

A rota `/conta/pedidos` sem id mostra a lista + pré-seleciona o pedido mais recente no painel direito (desktop) ou só a lista (mobile).

Controller: estender o `Cliente\UsuarioController` existente (não criar `PedidoController` novo — pedidos do cliente são parte do "self-service da conta", coerente com onde já está `enderecos`).

```php
public function pedidos(Request $request) {
    $pedidos = auth()->user()->usuario->vendas()
        ->with(['produtos.produto.imagens', 'desconto'])
        ->orderByDesc('data')->get();
    $selecionado = $pedidos->first();
    return view('cliente.usuario.pedidos', compact('pedidos', 'selecionado', 'categorias'));
}

public function pedidoDetalhe($id) {
    $pedido = auth()->user()->usuario->vendas()
        ->with(['produtos.produto.imagens', 'desconto'])
        ->findOrFail($id);
    // mesma view; vem com $selecionado = $pedido
    ...
}
```

**Segurança:** a query parte de `auth()->user()->usuario->vendas()` — Eloquent garante o escopo. Nunca buscar por `Venda::findOrFail($id)` direto (vazaria pedidos de outros clientes).

Falta investigar na implementação: a relação `User → Usuario` (modelo de domínio vs. modelo de auth do Laravel). O `Venda` aponta para `Usuario` via `usuario_id`. Confirmar o join no plano de implementação.

## Views (Blade)

Hierarquia seguindo a convenção do projeto (page Blade fina + componente reusável):

```
resources/views/cliente/usuario/pedidos.blade.php          # page
resources/views/components/cliente/usuario/pedidos.blade.php       # componente principal (master-detail)
resources/views/components/cliente/usuario/pedido-card.blade.php   # item da lista esquerda
resources/views/components/cliente/usuario/pedido-detalhe.blade.php # painel direito
resources/views/components/cliente/usuario/status-badge.blade.php   # badge reutilizável (recebe :status)
```

A page `pedidos.blade.php` recebe `$pedidos`, `$selecionado`, `$categorias` e delega tudo pro componente, igual `conta.blade.php` faz hoje.

### Layout master-detail

```
┌─────────────────────────────────────────────────────┐
│ [Header rosa-paleta — global]                       │
├──────────────┬──────────────────────────────────────┤
│ Minha Conta  │  Meus Pedidos                        │
│ (lilas)      │  ┌─────────┬──────────────────────┐  │
│              │  │ #1042   │  Pedido #1038        │  │
│ • Dados      │  │ R$ ...  │  02/mai/2026 [badge] │  │
│ • Endereços  │  │ [badge] │                      │  │
│ • Pedidos ←  │  ├─────────┤  Itens (lista)       │  │
│              │  │ #1038 ◀ │  Totais              │  │
│              │  │ ativo   │  Endereço            │  │
│              │  │ [badge] │                      │  │
│              │  ├─────────┤                      │  │
│              │  │ #1021   │                      │  │
│              │  │ ...     │                      │  │
│              │  └─────────┴──────────────────────┘  │
└──────────────┴──────────────────────────────────────┘
```

Grid Tailwind: `grid grid-cols-[280px_1fr]` na sidebar de conta (já existe), e dentro do `account-main` `grid grid-cols-[300px_1fr] gap-5`.

### Lista (esquerda)

Cada item da lista é um `<a href="{{ route('usuario.pedidos.detalhe', $pedido->id) }}">` com:
- número do pedido (`#{{ $pedido->id }}`) — `font-bold text-azul-paleta`
- total → direita
- data → `text-cinza-paleta text-sm`
- `<x-cliente.usuario.status-badge :status="$pedido->status"/>`
- item ativo (id == selecionado.id) recebe `bg-white border-l-3 border-verde-paleta` e shadow

### Detalhe (direita)

- Cabeçalho: "Pedido #{id}", data por extenso, badge de status, à direita "forma de pagamento" (placeholder — ainda não temos campo no schema; mostrar literal "PIX" por ora — feio mas honesto até criarmos `forma_pagamento`).
- Section label "Itens do pedido" → lista de `produto_venda` com thumbnail, nome, qty, preço unitário. Thumbnail vem de `produto.imagens` (primeira imagem) ou placeholder gradient se sem imagem.
- Totais: subtotal, linha de cupom se `desconto_id` não-nulo, total pago em destaque (`border-top: 2px solid azul-paleta`).
- "Entregue em": endereço — **decisão aberta**: hoje `vendas` não tem snapshot do endereço, só `usuario_id`. Mostrar endereço atual do cliente é incorreto (poderia ter mudado). Soluções: (a) snapshot na venda em migration futura, (b) exibir só o nome do cliente sem endereço, (c) mostrar endereço atual com aviso "endereço cadastrado na sua conta". **Escolha:** (b) por ora — sem endereço — para não dar informação errada. Anotar como follow-up.

### Mobile (< 768px)

- `/conta/pedidos` → só a lista, ocupando largura cheia. Cada card vira link pra `/conta/pedidos/{id}`.
- `/conta/pedidos/{id}` → só o detalhe, com botão "← voltar para meus pedidos" no topo.
- Implementar com `md:` breakpoint do Tailwind: a coluna do detalhe é `hidden md:block` quando estamos em `/conta/pedidos`; quando estamos em `/conta/pedidos/{id}`, a lista é `hidden md:block` (controlado por flag `$showList`/`$showDetail` vinda do controller).

## Componente status-badge

Componente Blade único para reuso na lista + detalhe + futura tela admin. Recebe `:status` e mapeia internamente:

```blade
@props(['status'])
@php
  $map = [
    'pendente'  => ['Pendente',  'bg-cinza-100 text-azul-paleta'],
    'pago'      => ['Pago',      'bg-azul-100 text-azul-700'],
    'enviado'   => ['Enviado',   'bg-lilas-100 text-azul-paleta'],
    'entregue'  => ['Entregue',  'bg-verde-100 text-verde-700'],
    'cancelado' => ['Cancelado', 'bg-rosa-50 text-rosa-900'],
  ];
  [$label, $cls] = $map[$status] ?? ['—', 'bg-cinza-100 text-azul-paleta'];
@endphp
<span class="inline-block px-2 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider {{ $cls }}">{{ $label }}</span>
```

## Estado vazio

Cliente sem nenhum pedido: a coluna esquerda mostra texto "Você ainda não fez nenhum pedido" em `text-cinza-paleta` + botão `bg-rosa-paleta` "Explorar produtos" linkando pra `/`. O painel direito fica vazio (ou repete a CTA centrada).

## Cleanup associado

Como parte deste trabalho:
1. Remover `bg-amber-400` do `components/cliente/usuario/conta.blade.php` — trocar por `bg-lilas-paleta`, igual a sidebar nova.
2. O link "Meus Pedidos" no card lateral da `/conta` (atualmente texto solto sem rota) passa a apontar pra `route('usuario.pedidos')`.

## Fora de escopo (follow-ups identificados)

- Snapshot do endereço na `vendas` (migration nova) — exibir endereço correto do pedido histórico.
- Coluna `forma_pagamento` em `vendas` — hoje hardcoded "PIX" no mockup.
- Tela admin para mudar status (a coluna fica disponível, mas a UI admin é outro spec).
- Cancelamento pelo cliente — fora de escopo.
- Refatorar o resto do app para tirar `text-indigo-500` (uso difuso, não-bloqueante).

## Critérios de sucesso

- Cliente logado em `/conta/pedidos` vê todos os seus pedidos com status visível.
- Clicar num pedido na lista atualiza o painel direito (desktop) ou navega pra rota de detalhe (mobile).
- Cliente NÃO consegue acessar pedido de outro cliente — `findOrFail` via `auth()->user()->usuario->vendas()` retorna 404.
- Após finalizar checkout, o pedido aparece com status `pago`.
- Zero uso de `bg-amber-*`, `bg-indigo-*`, etc. no código novo.
- Migration de status roda sem quebrar dados existentes (default `pendente` para vendas antigas — aceitável; admin pode atualizar).
