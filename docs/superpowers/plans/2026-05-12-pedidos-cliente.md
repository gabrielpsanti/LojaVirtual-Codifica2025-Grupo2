# Página "Meus Pedidos" do cliente — plano de implementação

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Cliente logado consegue ver lista e detalhe de seus pedidos em `/conta/pedidos` (layout master-detail), com badges de status. Tabela `vendas` ganha coluna `status`. Paleta brand do projeto fica formalizada como regra.

**Architecture:** Página Laravel/Blade nova sob `/conta/pedidos[/{id}]`, ambas rotas renderizando a mesma view com `$selecionado` diferente. Página decomposta em 1 page + 4 components (lista, card de item, painel de detalhe, badge de status). Autorização via Eloquent scoping (`auth()->user()->vendas()`) — nunca `Venda::findOrFail` direto. Migration nova adiciona `status` string com default `pendente`.

**Tech Stack:** Laravel 13, PHP 8.4, MySQL 8 (prod) / sqlite :memory: (testes), Tailwind 4 (paleta brand já em `resources/css/app.css`), PHPUnit 12.

**Convenções importantes deste codebase:**
- Tudo em pt-BR: rotas, controllers, models, colunas, views. Mantenha o padrão.
- Auth model é `App\Models\Usuario` (não `User`). `auth()->user()` retorna `Usuario`.
- Comandos rodam dentro do container: `docker compose exec app <cmd>`. Se der "permission denied" no socket do Docker, prefixe com `sg docker -c "..."`.
- Nunca dar `git push` — o usuário pede que o agente jamais publique.
- Cores default do Tailwind (`amber-*`, `indigo-*`, etc.) são **proibidas** em código novo. Use só os tokens `*-paleta`, `*-50`...`*-950` do `app.css`.
- Testes usam sqlite `:memory:` (ver `phpunit.xml`); migrations rodam toda vez. Use `RefreshDatabase` trait.

---

## File Structure

**Migrations**
- Create: `database/migrations/2026_05_12_000000_add_status_to_vendas_table.php`

**Models**
- Modify: `app/Models/Venda.php` (adiciona constantes de status + cast)

**Factories** (preenchendo stubs vazios existentes)
- Modify: `database/factories/UsuarioFactory.php`
- Modify: `database/factories/ProdutoFactory.php`
- Modify: `database/factories/VendaFactory.php`
- Modify: `database/factories/ProdutoVendaFactory.php`
- Create: `database/factories/CategoriaFactory.php` (não existe; precisamos pra criar Produto)

**Routes**
- Modify: `routes/web.php` (adiciona 2 rotas dentro do grupo `auth`)

**Controller**
- Modify: `app/Http/Controllers/Cliente/UsuarioController.php` (adiciona métodos `pedidos` e `pedidoDetalhe`)

**Views**
- Create: `resources/views/cliente/usuario/pedidos.blade.php` (page Blade fina)
- Create: `resources/views/components/cliente/usuario/pedidos.blade.php` (componente master-detail)
- Create: `resources/views/components/cliente/usuario/pedido-card.blade.php` (item da lista esquerda)
- Create: `resources/views/components/cliente/usuario/pedido-detalhe.blade.php` (painel direito)
- Create: `resources/views/components/cliente/usuario/status-badge.blade.php` (badge reutilizável)
- Modify: `resources/views/components/cliente/usuario/conta.blade.php` (cleanup do `bg-amber-400`, link "Meus Pedidos")

**Tests**
- Create: `tests/Feature/Cliente/PedidosTest.php` (todos os feature tests da feature)
- Create: `tests/Feature/Models/VendaStatusTest.php` (constantes + migration)

---

## Task 1 — Migration `status` em `vendas` + constantes no model `Venda` (TDD)

**Files:**
- Create: `database/migrations/2026_05_12_000000_add_status_to_vendas_table.php`
- Modify: `app/Models/Venda.php`
- Test: `tests/Feature/Models/VendaStatusTest.php`

- [ ] **Step 1 — Escrever o teste falhante**

Crie `tests/Feature/Models/VendaStatusTest.php`:

```php
<?php

namespace Tests\Feature\Models;

use App\Models\Venda;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class VendaStatusTest extends TestCase
{
    use RefreshDatabase;

    public function test_migration_adiciona_coluna_status_com_default_pendente(): void
    {
        $this->assertTrue(Schema::hasColumn('vendas', 'status'));

        // Uma venda criada sem status explícito deve nascer 'pendente'.
        // Aqui inserimos via query builder para não depender ainda da factory.
        $usuarioId = \App\Models\Usuario::factory()->create()->id;
        $id = \DB::table('vendas')->insertGetId([
            'usuario_id' => $usuarioId,
            'data' => now()->toDateString(),
            'preco_total' => 100,
            'preco_total_com_desconto' => 100,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->assertSame('pendente', \DB::table('vendas')->where('id', $id)->value('status'));
    }

    public function test_venda_tem_constantes_de_status(): void
    {
        $this->assertSame('pendente',  Venda::STATUS_PENDENTE);
        $this->assertSame('pago',      Venda::STATUS_PAGO);
        $this->assertSame('enviado',   Venda::STATUS_ENVIADO);
        $this->assertSame('entregue',  Venda::STATUS_ENTREGUE);
        $this->assertSame('cancelado', Venda::STATUS_CANCELADO);

        $this->assertSame(
            ['pendente', 'pago', 'enviado', 'entregue', 'cancelado'],
            Venda::statusesValidos()
        );
    }
}
```

- [ ] **Step 2 — Rodar e confirmar que falha**

```bash
docker compose exec app php artisan test --filter=VendaStatusTest
```

Esperado: falha em `assertTrue(Schema::hasColumn(...))` e/ou `Undefined constant STATUS_*`. (A `UsuarioFactory` ainda está com stub vazio — vai falhar antes; isso é OK, a Task 2 vai consertar. Por enquanto basta confirmar que o teste roda e falha por falta do schema/constantes.)

- [ ] **Step 3 — Criar a migration**

Crie `database/migrations/2026_05_12_000000_add_status_to_vendas_table.php`:

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('vendas', function (Blueprint $table) {
            $table->string('status', 20)
                ->default('pendente')
                ->after('preco_total_com_desconto');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::table('vendas', function (Blueprint $table) {
            $table->dropIndex(['status']);
            $table->dropColumn('status');
        });
    }
};
```

- [ ] **Step 4 — Adicionar constantes e helper no model `Venda`**

Edite `app/Models/Venda.php` adicionando, logo após `protected $table = 'vendas';`:

```php
public const STATUS_PENDENTE  = 'pendente';
public const STATUS_PAGO      = 'pago';
public const STATUS_ENVIADO   = 'enviado';
public const STATUS_ENTREGUE  = 'entregue';
public const STATUS_CANCELADO = 'cancelado';

public static function statusesValidos(): array
{
    return [
        self::STATUS_PENDENTE,
        self::STATUS_PAGO,
        self::STATUS_ENVIADO,
        self::STATUS_ENTREGUE,
        self::STATUS_CANCELADO,
    ];
}

protected $fillable = [
    'usuario_id', 'data', 'preco_total', 'desconto_id',
    'preco_total_com_desconto', 'status',
];

protected $casts = [
    'data' => 'date',
];
```

> Por que `$fillable`: o model hoje não tem fillable, então `Venda::create([...])` ignoraria os campos. Os factories da Task 2 vão usar `create`.

- [ ] **Step 5 — Rodar o teste — deve passar (depois da Task 2 a parte do `UsuarioFactory` também rodará, mas as constantes já estão prontas)**

```bash
docker compose exec app php artisan test --filter=VendaStatusTest::test_venda_tem_constantes_de_status
```

Esperado: PASS para o teste de constantes. O teste da coluna ainda pode falhar pelo `UsuarioFactory` vazio — isso é esperado e será resolvido na Task 2.

- [ ] **Step 6 — Commit**

```bash
git add database/migrations/2026_05_12_000000_add_status_to_vendas_table.php \
        app/Models/Venda.php \
        tests/Feature/Models/VendaStatusTest.php
git commit -m "feat(vendas): adiciona coluna status com constantes no model Venda"
```

---

## Task 2 — Preencher factories (Usuario, Categoria, Produto, Venda, ProdutoVenda)

Todas as factories estão como stub vazio. Sem isso não dá pra escrever feature test que precise de venda no banco.

**Files:**
- Modify: `database/factories/UsuarioFactory.php`
- Create: `database/factories/CategoriaFactory.php`
- Modify: `database/factories/ProdutoFactory.php`
- Modify: `database/factories/VendaFactory.php`
- Modify: `database/factories/ProdutoVendaFactory.php`
- Modify: `app/Models/Categoria.php` (adicionar `use HasFactory`)
- Modify: `app/Models/Produto.php` (adicionar `use HasFactory` se não tiver)

- [ ] **Step 1 — Escrever teste falhante simples para todas as factories**

Adicione um teste em `tests/Feature/Models/VendaStatusTest.php` (mesmo arquivo, no final da classe):

```php
public function test_factories_constroem_grafo_minimo_de_venda(): void
{
    $venda = \App\Models\Venda::factory()
        ->has(\App\Models\ProdutoVenda::factory()->count(2), 'produtos')
        ->create();

    $this->assertNotNull($venda->id);
    $this->assertNotNull($venda->cliente);          // belongsTo Usuario
    $this->assertCount(2, $venda->produtos);         // hasMany ProdutoVenda
    $this->assertNotNull($venda->produtos[0]->produto); // belongsTo Produto
}
```

- [ ] **Step 2 — Rodar e confirmar falha**

```bash
docker compose exec app php artisan test --filter=test_factories_constroem_grafo_minimo_de_venda
```

Esperado: erro (provavelmente sobre campos faltando ou factory retornando array vazio).

- [ ] **Step 3 — Preencher `UsuarioFactory`**

Substitua o conteúdo do `definition()` em `database/factories/UsuarioFactory.php` (já existe o arquivo, definition retorna vazio):

```php
public function definition(): array
{
    return [
        'nome' => fake()->name(),
        'email' => fake()->unique()->safeEmail(),
        'password' => bcrypt('password'),
        'cpf_cnpj' => fake()->numerify('###########'), // 11 dígitos, suficiente
    ];
}
```

- [ ] **Step 4 — Criar `CategoriaFactory` e ligar no model**

Crie `database/factories/CategoriaFactory.php`:

```php
<?php

namespace Database\Factories;

use App\Models\Categoria;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Categoria>
 */
class CategoriaFactory extends Factory
{
    protected $model = Categoria::class;

    public function definition(): array
    {
        $nome = fake()->unique()->words(2, true);
        return [
            'nome' => ucfirst($nome),
            'rota' => Str::slug($nome),
        ];
    }
}
```

E em `app/Models/Categoria.php`, garanta que o trait `HasFactory` está ligado (cheque com `head -15` antes de editar). Se não tiver:

```php
use Illuminate\Database\Eloquent\Factories\HasFactory;
// dentro da classe:
use HasFactory;
```

- [ ] **Step 5 — Preencher `ProdutoFactory`**

Substitua o conteúdo do `definition()` em `database/factories/ProdutoFactory.php`:

```php
public function definition(): array
{
    return [
        'nome' => fake()->words(3, true),
        'preco' => fake()->randomFloat(2, 10, 500),
        'categoria_id' => \App\Models\Categoria::factory(),
        'descricao' => fake()->sentence(),
        'quantidade' => fake()->numberBetween(1, 100),
        'imagem' => 'placeholder.png',
    ];
}
```

E em `app/Models/Produto.php` garanta `use HasFactory;` no `use` da classe (se não tiver).

- [ ] **Step 6 — Preencher `VendaFactory`**

Substitua o conteúdo do `definition()` em `database/factories/VendaFactory.php`:

```php
public function definition(): array
{
    $total = fake()->randomFloat(2, 50, 800);
    return [
        'usuario_id' => \App\Models\Usuario::factory(),
        'data' => fake()->dateTimeBetween('-90 days')->format('Y-m-d'),
        'preco_total' => $total,
        'preco_total_com_desconto' => $total,
        'status' => 'pendente',
    ];
}
```

- [ ] **Step 7 — Preencher `ProdutoVendaFactory`**

Substitua o `definition()` em `database/factories/ProdutoVendaFactory.php`:

```php
public function definition(): array
{
    $qtd = fake()->numberBetween(1, 4);
    $preco = fake()->randomFloat(2, 10, 200);
    return [
        'venda_id' => \App\Models\Venda::factory(),
        'produto_id' => \App\Models\Produto::factory(),
        'quantidade' => $qtd,
        'preco_unitario' => $preco,
        'preco_com_desconto' => $preco,
    ];
}
```

- [ ] **Step 8 — Rodar e confirmar PASS**

```bash
docker compose exec app php artisan test --filter=VendaStatusTest
```

Esperado: TODOS os 3 testes da `VendaStatusTest` passam.

- [ ] **Step 9 — Commit**

```bash
git add database/factories/ app/Models/Categoria.php app/Models/Produto.php tests/Feature/Models/VendaStatusTest.php
git commit -m "feat(factories): preenche factories (usuario, categoria, produto, venda, produto_venda)"
```

---

## Task 3 — Componente `status-badge` (TDD)

A menor unidade visual. Reutilizável na lista, no detalhe, e na futura tela admin.

**Files:**
- Create: `resources/views/components/cliente/usuario/status-badge.blade.php`
- Test: `tests/Feature/Cliente/PedidosTest.php`

- [ ] **Step 1 — Criar diretório de teste e escrever teste falhante**

Crie `tests/Feature/Cliente/PedidosTest.php`:

```php
<?php

namespace Tests\Feature\Cliente;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PedidosTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @dataProvider statusProvider
     */
    public function test_status_badge_renderiza_label_e_classes_corretas(string $status, string $labelEsperado, string $classeEsperada): void
    {
        $view = $this->blade(
            '<x-cliente.usuario.status-badge :status="$status"/>',
            ['status' => $status]
        );

        $view->assertSee($labelEsperado);
        $view->assertSee($classeEsperada, false);
    }

    public static function statusProvider(): array
    {
        return [
            ['pendente',  'Pendente',  'bg-cinza-100'],
            ['pago',      'Pago',      'bg-azul-100'],
            ['enviado',   'Enviado',   'bg-lilas-100'],
            ['entregue',  'Entregue',  'bg-verde-100'],
            ['cancelado', 'Cancelado', 'bg-rosa-50'],
        ];
    }

    public function test_status_badge_desconhecido_renderiza_neutro(): void
    {
        $view = $this->blade(
            '<x-cliente.usuario.status-badge :status="$status"/>',
            ['status' => 'algo-inexistente']
        );
        $view->assertSee('—');
    }
}
```

- [ ] **Step 2 — Rodar e confirmar falha**

```bash
docker compose exec app php artisan test --filter=PedidosTest
```

Esperado: falha "Unable to locate a class or view for component [cliente.usuario.status-badge]".

- [ ] **Step 3 — Implementar o componente**

Crie `resources/views/components/cliente/usuario/status-badge.blade.php`:

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
<span {{ $attributes->class(['inline-block px-2 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider', $cls]) }}>
    {{ $label }}
</span>
```

- [ ] **Step 4 — Rodar e confirmar PASS**

```bash
docker compose exec app php artisan test --filter=PedidosTest
```

Esperado: 6 testes do badge passam.

- [ ] **Step 5 — Commit**

```bash
git add resources/views/components/cliente/usuario/status-badge.blade.php tests/Feature/Cliente/PedidosTest.php
git commit -m "feat(views): componente status-badge para pedidos"
```

---

## Task 4 — Rota + método `pedidos` (lista, sem detalhe ainda) (TDD)

**Files:**
- Modify: `routes/web.php`
- Modify: `app/Http/Controllers/Cliente/UsuarioController.php`
- Create: `resources/views/cliente/usuario/pedidos.blade.php` (versão mínima — só renderiza dados)
- Test: `tests/Feature/Cliente/PedidosTest.php`

- [ ] **Step 1 — Escrever testes falhantes (adicionar à `PedidosTest`)**

Adicione esses métodos ao final da classe `PedidosTest`:

```php
public function test_guest_redirecionado_para_login_ao_acessar_pedidos(): void
{
    $this->get('/conta/pedidos')->assertRedirect('/login');
}

public function test_cliente_logado_ve_seus_pedidos_na_lista(): void
{
    $usuario = \App\Models\Usuario::factory()->create();
    $v1 = \App\Models\Venda::factory()->create([
        'usuario_id' => $usuario->id,
        'preco_total' => 189.90, 'preco_total_com_desconto' => 189.90,
        'status' => 'enviado',
    ]);
    $v2 = \App\Models\Venda::factory()->create([
        'usuario_id' => $usuario->id,
        'preco_total' => 74.00, 'preco_total_com_desconto' => 66.60,
        'status' => 'entregue',
    ]);

    $response = $this->actingAs($usuario)->get('/conta/pedidos');

    $response->assertOk();
    $response->assertSee("#{$v1->id}");
    $response->assertSee("#{$v2->id}");
    $response->assertSee('189,90');     // total formatado pt-BR
    $response->assertSee('66,60');
    $response->assertSee('Enviado');     // status badge
    $response->assertSee('Entregue');
}

public function test_cliente_nao_ve_pedidos_de_outros_clientes(): void
{
    $eu    = \App\Models\Usuario::factory()->create();
    $outro = \App\Models\Usuario::factory()->create();
    $vendaDoOutro = \App\Models\Venda::factory()->create([
        'usuario_id' => $outro->id,
        'preco_total' => 999.99, 'preco_total_com_desconto' => 999.99,
    ]);

    $response = $this->actingAs($eu)->get('/conta/pedidos');

    $response->assertOk();
    $response->assertDontSee("#{$vendaDoOutro->id}");
    $response->assertDontSee('999,99');
}
```

- [ ] **Step 2 — Rodar e confirmar falha**

```bash
docker compose exec app php artisan test --filter=PedidosTest
```

Esperado: 3 novos testes falham (404 ou rota não encontrada).

- [ ] **Step 3 — Adicionar a rota**

Edite `routes/web.php`. Dentro do grupo `Route::middleware('auth')->group(function () { ... })`, **logo após a rota `usuario.enderecos.atualizar`** (linha ~52), adicione:

```php
Route::get('/conta/pedidos',      [UsuarioController::class, 'pedidos'])->name('usuario.pedidos');
Route::get('/conta/pedidos/{id}', [UsuarioController::class, 'pedidoDetalhe'])->whereNumber('id')->name('usuario.pedidos.detalhe');
```

> O `whereNumber('id')` é importante: sem ele, a rota greedy `/{categoria}` no fim do arquivo poderia conflitar. Mas como `/conta/pedidos/{id}` tem 3 segmentos e `/{categoria}` tem 1, não há conflito direto — o `whereNumber` é defesa em profundidade.

- [ ] **Step 4 — Adicionar método `pedidos` ao controller**

Edite `app/Http/Controllers/Cliente/UsuarioController.php`. Adicione o método ao final da classe (e adicione `use App\Models\Venda;` no topo se não tiver):

```php
public function pedidos(Request $request)
{
    $categorias = Categoria::all();
    $usuario = auth()->user();

    $pedidos = $usuario->vendas()
        ->with(['produtos.produto.imagens', 'desconto'])
        ->orderByDesc('data')
        ->orderByDesc('id')
        ->get();

    $selecionado = $pedidos->first(); // pode ser null (sem pedidos)

    return view('cliente.usuario.pedidos', compact('pedidos', 'selecionado', 'categorias', 'usuario'));
}
```

- [ ] **Step 5 — Criar page Blade mínima (versão suficiente pros testes desta task)**

Crie `resources/views/cliente/usuario/pedidos.blade.php`:

```blade
<x-cliente.layout :categorias="$categorias">
    <div class="max-w-6xl mx-auto px-6 py-8">
        <h1 class="text-2xl font-bold text-azul-paleta mb-6">Meus Pedidos</h1>

        @forelse($pedidos as $pedido)
            <div class="border border-cinza-100 rounded-lg p-4 mb-3">
                <div class="flex justify-between items-start">
                    <div>
                        <div class="font-bold text-azul-paleta">#{{ $pedido->id }}</div>
                        <div class="text-sm text-cinza-paleta">
                            {{ $pedido->data?->format('d/m/Y') }}
                        </div>
                        <x-cliente.usuario.status-badge :status="$pedido->status" class="mt-2"/>
                    </div>
                    <div class="font-bold text-azul-paleta">
                        R$ {{ number_format($pedido->preco_total_com_desconto, 2, ',', '.') }}
                    </div>
                </div>
            </div>
        @empty
            <p class="text-cinza-paleta">Você ainda não fez nenhum pedido.</p>
        @endforelse
    </div>
</x-cliente.layout>
```

- [ ] **Step 6 — Rodar e confirmar PASS**

```bash
docker compose exec app php artisan test --filter=PedidosTest
```

Esperado: todos passam (badge + 3 novos).

- [ ] **Step 7 — Commit**

```bash
git add routes/web.php app/Http/Controllers/Cliente/UsuarioController.php resources/views/cliente/usuario/pedidos.blade.php tests/Feature/Cliente/PedidosTest.php
git commit -m "feat(pedidos): rota /conta/pedidos lista pedidos do cliente autenticado"
```

---

## Task 5 — Rota + método `pedidoDetalhe` (autorização por escopo) (TDD)

**Files:**
- Modify: `app/Http/Controllers/Cliente/UsuarioController.php`
- Test: `tests/Feature/Cliente/PedidosTest.php`

- [ ] **Step 1 — Escrever testes falhantes (adicionar à `PedidosTest`)**

```php
public function test_cliente_ve_detalhe_do_proprio_pedido(): void
{
    $usuario = \App\Models\Usuario::factory()->create();
    $venda = \App\Models\Venda::factory()
        ->has(\App\Models\ProdutoVenda::factory()->count(1)->state([
            'quantidade' => 1, 'preco_unitario' => 74.00, 'preco_com_desconto' => 74.00,
        ]), 'produtos')
        ->create([
            'usuario_id' => $usuario->id,
            'preco_total' => 74.00, 'preco_total_com_desconto' => 74.00,
            'status' => 'entregue',
        ]);

    $response = $this->actingAs($usuario)->get("/conta/pedidos/{$venda->id}");

    $response->assertOk();
    $response->assertSee("Pedido #{$venda->id}");
    $response->assertSee('Entregue');
}

public function test_cliente_recebe_404_ao_acessar_pedido_de_outro_cliente(): void
{
    $eu    = \App\Models\Usuario::factory()->create();
    $outro = \App\Models\Usuario::factory()->create();
    $vendaDoOutro = \App\Models\Venda::factory()->create(['usuario_id' => $outro->id]);

    $this->actingAs($eu)
        ->get("/conta/pedidos/{$vendaDoOutro->id}")
        ->assertNotFound();
}

public function test_guest_redirecionado_no_detalhe(): void
{
    $venda = \App\Models\Venda::factory()->create();
    $this->get("/conta/pedidos/{$venda->id}")->assertRedirect('/login');
}
```

- [ ] **Step 2 — Rodar e confirmar falha**

```bash
docker compose exec app php artisan test --filter=PedidosTest
```

Esperado: 3 novos testes falham (método/view não existem).

- [ ] **Step 3 — Adicionar método `pedidoDetalhe` ao controller**

Edite `app/Http/Controllers/Cliente/UsuarioController.php`, adicionando após `pedidos`:

```php
public function pedidoDetalhe(Request $request, int $id)
{
    $categorias = Categoria::all();
    $usuario = auth()->user();

    // findOrFail no escopo do usuário — pedidos de outros viram 404.
    $selecionado = $usuario->vendas()
        ->with(['produtos.produto.imagens', 'desconto'])
        ->findOrFail($id);

    // A lista continua sendo carregada (master-detail).
    $pedidos = $usuario->vendas()
        ->with(['desconto'])
        ->orderByDesc('data')->orderByDesc('id')->get();

    return view('cliente.usuario.pedidos', compact('pedidos', 'selecionado', 'categorias', 'usuario'));
}
```

- [ ] **Step 4 — Atualizar a page Blade pra mostrar `$selecionado`**

Edite `resources/views/cliente/usuario/pedidos.blade.php`. Logo após o `<h1>`, adicione (mantenha o `@forelse` abaixo):

```blade
@isset($selecionado)
    <div class="mb-6 p-4 border border-cinza-100 rounded-lg bg-lilas-50">
        <h2 class="font-bold text-azul-paleta">Pedido #{{ $selecionado->id }}</h2>
        <x-cliente.usuario.status-badge :status="$selecionado->status" class="mt-1"/>
    </div>
@endisset
```

> Esse é um placeholder mínimo só pros testes passarem. Task 6 substitui por um componente completo.

- [ ] **Step 5 — Rodar e confirmar PASS**

```bash
docker compose exec app php artisan test --filter=PedidosTest
```

Esperado: todos passam.

- [ ] **Step 6 — Commit**

```bash
git add app/Http/Controllers/Cliente/UsuarioController.php resources/views/cliente/usuario/pedidos.blade.php tests/Feature/Cliente/PedidosTest.php
git commit -m "feat(pedidos): detalhe /conta/pedidos/{id} com escopo do cliente"
```

---

## Task 6 — Decompor em componentes master-detail (paleta brand)

Substitui o Blade mínimo da page por uma estrutura limpa com 3 componentes. Não muda comportamento — só refatora e aplica a paleta.

**Files:**
- Modify: `resources/views/cliente/usuario/pedidos.blade.php`
- Create: `resources/views/components/cliente/usuario/pedidos.blade.php` (orquestrador)
- Create: `resources/views/components/cliente/usuario/pedido-card.blade.php`
- Create: `resources/views/components/cliente/usuario/pedido-detalhe.blade.php`

- [ ] **Step 1 — Os testes existentes da `PedidosTest` cobrem isso**

Não precisa de teste novo. Mantemos a verificação: `assertSee` do número, total, status já vão validar.

- [ ] **Step 2 — Criar `pedido-card.blade.php`**

Crie `resources/views/components/cliente/usuario/pedido-card.blade.php`:

```blade
@props(['pedido', 'ativo' => false])

@php
    $classesBase = 'block px-4 py-3 rounded-md mb-1 border-l-3 border-transparent transition-colors';
    $classesAtivo = $ativo
        ? 'bg-white border-verde-paleta shadow-sm'
        : 'hover:bg-white/60';
@endphp

<a href="{{ route('usuario.pedidos.detalhe', $pedido->id) }}"
   class="{{ $classesBase }} {{ $classesAtivo }}">
    <div class="flex justify-between items-center">
        <span class="font-bold text-azul-paleta">#{{ $pedido->id }}</span>
        <span class="text-sm font-semibold text-azul-paleta">
            R$ {{ number_format($pedido->preco_total_com_desconto, 2, ',', '.') }}
        </span>
    </div>
    <div class="text-xs text-cinza-paleta mt-1">
        {{ $pedido->data?->format('d/m/Y') }}
    </div>
    <x-cliente.usuario.status-badge :status="$pedido->status" class="mt-2"/>
</a>
```

- [ ] **Step 3 — Criar `pedido-detalhe.blade.php`**

Crie `resources/views/components/cliente/usuario/pedido-detalhe.blade.php`:

```blade
@props(['pedido' => null])

@if($pedido === null)
    <div class="bg-white border border-cinza-100 rounded-lg p-8 text-center text-cinza-paleta">
        Selecione um pedido à esquerda para ver os detalhes.
    </div>
@else
    <div class="bg-white border border-cinza-100 rounded-lg p-6">
        <div class="flex justify-between items-start mb-1">
            <div>
                <h3 class="text-xl font-bold text-azul-paleta">Pedido #{{ $pedido->id }}</h3>
                <div class="text-sm text-cinza-paleta mt-1">
                    {{ optional($pedido->data)->translatedFormat('d \d\e F \d\e Y') }}
                    · <x-cliente.usuario.status-badge :status="$pedido->status"/>
                </div>
            </div>
            <div class="text-right text-xs text-cinza-paleta">
                <div>Forma de pagamento</div>
                <div class="text-azul-paleta font-semibold text-sm mt-0.5">PIX</div>
            </div>
        </div>

        <div class="mt-6 text-[11px] uppercase tracking-wider font-semibold text-cinza-paleta mb-2">
            Itens do pedido
        </div>

        @foreach($pedido->produtos as $item)
            <div class="flex items-center gap-3 py-3 border-t border-cinza-100 first:border-t-cinza-200">
                <div class="w-13 h-13 rounded-md flex-shrink-0 bg-gradient-to-br from-lilas-paleta to-cinza-paleta"></div>
                <div class="flex-1">
                    <div class="font-semibold text-azul-paleta text-sm">
                        {{ $item->produto?->nome ?? 'Produto removido' }}
                    </div>
                    <div class="text-xs text-cinza-paleta">{{ $item->quantidade }} unidade(s)</div>
                </div>
                <div class="font-semibold text-azul-paleta">
                    R$ {{ number_format($item->preco_com_desconto * $item->quantidade, 2, ',', '.') }}
                </div>
            </div>
        @endforeach

        <div class="mt-5 pt-4 border-t border-cinza-100 space-y-1 text-sm text-cinza-paleta">
            <div class="flex justify-between">
                <span>Subtotal</span>
                <span>R$ {{ number_format($pedido->preco_total, 2, ',', '.') }}</span>
            </div>
            @if($pedido->desconto)
                <div class="flex justify-between">
                    <span>Cupom <strong>{{ $pedido->desconto->codigo ?? '' }}</strong></span>
                    <span class="text-verde-700">
                        − R$ {{ number_format($pedido->preco_total - $pedido->preco_total_com_desconto, 2, ',', '.') }}
                    </span>
                </div>
            @endif
            <div class="flex justify-between mt-2 pt-3 border-t-2 border-azul-paleta text-base font-bold text-azul-paleta">
                <span>Total pago</span>
                <span>R$ {{ number_format($pedido->preco_total_com_desconto, 2, ',', '.') }}</span>
            </div>
        </div>
    </div>
@endif
```

> Sobre `$pedido->desconto->codigo`: confira se a tabela `descontos` tem coluna `codigo`. Se não tiver, troque por `$pedido->desconto->id` ou remova essa parte do `<strong>`. Investigue antes:
> ```bash
> docker compose exec app php artisan tinker --execute='echo Schema::getColumnListing("descontos")'
> ```

- [ ] **Step 4 — Criar `pedidos.blade.php` (orquestrador master-detail)**

Crie `resources/views/components/cliente/usuario/pedidos.blade.php`:

```blade
@props(['pedidos', 'selecionado'])

<div class="grid grid-cols-1 md:grid-cols-[1fr_3fr] gap-0 min-h-[500px]">

    {{-- Sidebar reaproveitada da página /conta. Aqui, com "Meus Pedidos" ativo. --}}
    <aside class="bg-lilas-paleta p-5 text-azul-paleta">
        <h1 class="text-xl font-bold mb-4">Minha Conta</h1>
        <a href="{{ route('usuario.index') }}"
           class="block bg-lilas-50 px-3 py-2 mb-2 rounded text-sm">Dados Pessoais</a>
        <a href="{{ route('usuario.enderecos') }}"
           class="block bg-lilas-50 px-3 py-2 mb-2 rounded text-sm">Meus Endereços</a>
        <span class="block bg-white px-3 py-2 mb-2 rounded text-sm font-semibold border-l-3 border-verde-paleta">
            Meus Pedidos
        </span>
    </aside>

    <main class="p-6 md:p-8 bg-white">
        <h2 class="text-2xl font-bold text-azul-paleta mb-5">Meus Pedidos</h2>

        @if($pedidos->isEmpty())
            <div class="text-center py-12">
                <p class="text-cinza-paleta mb-4">Você ainda não fez nenhum pedido.</p>
                <a href="{{ route('index') }}"
                   class="inline-block bg-rosa-paleta hover:bg-rosa-100 text-azul-paleta font-bold px-4 py-2 rounded">
                    Explorar produtos
                </a>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-[18rem_1fr] gap-5">
                <div class="bg-lilas-50/40 rounded-lg p-2 border border-cinza-100">
                    @foreach($pedidos as $pedido)
                        <x-cliente.usuario.pedido-card
                            :pedido="$pedido"
                            :ativo="$selecionado && $selecionado->id === $pedido->id"/>
                    @endforeach
                </div>
                <x-cliente.usuario.pedido-detalhe :pedido="$selecionado"/>
            </div>
        @endif
    </main>
</div>
```

- [ ] **Step 5 — Atualizar page `cliente/usuario/pedidos.blade.php` para usar o componente**

Substitua TODO o conteúdo de `resources/views/cliente/usuario/pedidos.blade.php` por:

```blade
<x-cliente.layout :categorias="$categorias">
    <x-cliente.usuario.pedidos :pedidos="$pedidos" :selecionado="$selecionado"/>
</x-cliente.layout>
```

- [ ] **Step 6 — Rodar e confirmar PASS**

```bash
docker compose exec app php artisan test --filter=PedidosTest
```

Esperado: todos os testes da `PedidosTest` continuam passando (a refatoração não muda comportamento observável).

- [ ] **Step 7 — Commit**

```bash
git add resources/views/cliente/usuario/pedidos.blade.php \
        resources/views/components/cliente/usuario/pedidos.blade.php \
        resources/views/components/cliente/usuario/pedido-card.blade.php \
        resources/views/components/cliente/usuario/pedido-detalhe.blade.php
git commit -m "feat(pedidos): decompõe page em components master-detail"
```

---

## Task 7 — Empty state com CTA (TDD)

**Files:**
- Test: `tests/Feature/Cliente/PedidosTest.php`

- [ ] **Step 1 — Escrever teste falhante**

Adicione à `PedidosTest`:

```php
public function test_empty_state_quando_cliente_nao_tem_pedidos(): void
{
    $usuario = \App\Models\Usuario::factory()->create();

    $response = $this->actingAs($usuario)->get('/conta/pedidos');

    $response->assertOk();
    $response->assertSee('Você ainda não fez nenhum pedido');
    $response->assertSee('Explorar produtos');
    $response->assertSee(route('index'), false);
}
```

- [ ] **Step 2 — Rodar — DEVE JÁ PASSAR**

O `pedidos.blade.php` (componente) da Task 6 já tem o `@if($pedidos->isEmpty())` cobrindo isso.

```bash
docker compose exec app php artisan test --filter=test_empty_state
```

Esperado: PASS imediato (verificação de cobertura). Se falhar, retorne à Task 6 e confira a parte do `isEmpty()` no componente.

- [ ] **Step 3 — Commit**

```bash
git add tests/Feature/Cliente/PedidosTest.php
git commit -m "test(pedidos): cobre empty state da página de pedidos"
```

---

## Task 8 — Cleanup do `bg-amber-400` no `/conta` + link "Meus Pedidos"

A view existente `components/cliente/usuario/conta.blade.php` usa `bg-amber-400` (off-brand) e tem um `<a>Editar</a>` solto que vai ser o link para a nova página.

**Files:**
- Modify: `resources/views/components/cliente/usuario/conta.blade.php`
- Test: `tests/Feature/Cliente/PedidosTest.php`

- [ ] **Step 1 — Escrever teste falhante**

Adicione à `PedidosTest`:

```php
public function test_pagina_conta_nao_usa_cores_off_brand_e_linka_para_pedidos(): void
{
    $usuario = \App\Models\Usuario::factory()->create();

    $response = $this->actingAs($usuario)->get('/conta');

    $response->assertOk();
    $response->assertDontSee('bg-amber-400', false);
    $response->assertSee('bg-lilas-paleta', false);
    $response->assertSee(route('usuario.pedidos'), false);
}
```

- [ ] **Step 2 — Rodar e confirmar falha**

```bash
docker compose exec app php artisan test --filter=test_pagina_conta_nao_usa_cores_off_brand
```

Esperado: falha em `assertDontSee('bg-amber-400')` (a string ainda está no Blade).

- [ ] **Step 3 — Editar o componente conta**

Edite `resources/views/components/cliente/usuario/conta.blade.php`:

- Troque `bg-amber-400` por `bg-lilas-paleta` (linha 4).
- Troque `bg-lilas-100` por `bg-lilas-50` nos cards de dentro (linhas 10 e 19).
- Adicione, depois do segundo bloco "Meus Endereços" (logo após o `</div>` da linha ~26), uma nova seção "Meus Pedidos":

```blade
<div>
    <div class="grid grid-cols-2 p-2 bg-lilas-50">
        <h2 class="text-azul-paleta">Meus Pedidos</h2>
        <a href="{{ route('usuario.pedidos') }}" class="text-verde-paleta underline">Ver todos</a>
    </div>
</div>
```

> A seção "Meus pedidos" do lado direito do componente (que tem o `@foreach` vazio) pode ficar como está — ela vai exibir um resumo no futuro. Não removemos pra evitar regressão.

- [ ] **Step 4 — Rodar e confirmar PASS**

```bash
docker compose exec app php artisan test --filter=PedidosTest
```

Esperado: todos passam.

- [ ] **Step 5 — Verificação manual rápida no navegador**

```bash
# Stack já deve estar de pé. Se não estiver:
docker compose --profile dev up -d
docker compose exec app npm run build   # ou deixe o vite/node em dev compilando
```

Acesse `http://localhost/login`, entre com algum usuário (crie via tinker se preciso), navegue para `/conta` e `/conta/pedidos`. Confirme:
- Sidebar lilás (não mais laranja).
- Link "Ver todos" leva para `/conta/pedidos`.
- Página de pedidos com layout master-detail, badges com cores corretas.

> Para criar dados de teste rapidamente:
> ```bash
> docker compose exec app php artisan tinker --execute='
>   $u = App\Models\Usuario::factory()->create(["email" => "teste@loja.com"]);
>   App\Models\Venda::factory()->count(4)->has(App\Models\ProdutoVenda::factory()->count(2), "produtos")->create(["usuario_id" => $u->id]);
>   echo "Login: teste@loja.com / password";
> '
> ```

- [ ] **Step 6 — Commit**

```bash
git add resources/views/components/cliente/usuario/conta.blade.php tests/Feature/Cliente/PedidosTest.php
git commit -m "fix(conta): remove bg-amber-400 off-brand e linka Meus Pedidos"
```

---

## Task 9 — Comportamento responsivo (mobile) — sem TDD

Não há como testar `display: hidden` em PHPUnit (testar CSS responsivo requer browser tests, fora de escopo). Esta task é só implementação + verificação manual.

**Files:**
- Modify: `resources/views/components/cliente/usuario/pedidos.blade.php`

- [ ] **Step 1 — Ajustar grids para mobile**

O componente já usa `grid-cols-1 md:grid-cols-[18rem_1fr]` para lista + detalhe. Falta:

- Quando estamos na rota de **lista** (`$selecionado` é só o primeiro pedido carregado), no mobile o painel de detalhe distrai. Mostrar só a lista.
- Quando estamos na rota de **detalhe** (URL com `/{id}`), no mobile mostrar só o detalhe + botão "voltar".

Pra distinguir, use `request()->routeIs('usuario.pedidos.detalhe')`. Edite `resources/views/components/cliente/usuario/pedidos.blade.php` substituindo o bloco interno do `@else` por:

```blade
@php $isDetalheRoute = request()->routeIs('usuario.pedidos.detalhe'); @endphp

<div class="grid grid-cols-1 md:grid-cols-[18rem_1fr] gap-5">
    <div class="{{ $isDetalheRoute ? 'hidden md:block' : '' }} bg-lilas-50/40 rounded-lg p-2 border border-cinza-100">
        @foreach($pedidos as $pedido)
            <x-cliente.usuario.pedido-card
                :pedido="$pedido"
                :ativo="$selecionado && $selecionado->id === $pedido->id"/>
        @endforeach
    </div>
    <div class="{{ $isDetalheRoute ? '' : 'hidden md:block' }}">
        @if($isDetalheRoute)
            <a href="{{ route('usuario.pedidos') }}" class="inline-flex items-center gap-1 mb-3 text-sm text-verde-paleta md:hidden">
                ← voltar para meus pedidos
            </a>
        @endif
        <x-cliente.usuario.pedido-detalhe :pedido="$selecionado"/>
    </div>
</div>
```

- [ ] **Step 2 — Rodar suite completa de testes**

```bash
docker compose exec app php artisan test
```

Esperado: tudo passa. (Mudança não quebra comportamento testado — só esconde/mostra colunas conforme breakpoint.)

- [ ] **Step 3 — Verificação manual no navegador**

Abra DevTools, mude para viewport mobile (375px). Confirme:
- `/conta/pedidos` → só a lista, sem painel de detalhe.
- Clicar num card → vai para `/conta/pedidos/{id}` → só o detalhe + botão "← voltar".
- Volte ao desktop (>= 768px) → master-detail aparece junto.

- [ ] **Step 4 — Commit**

```bash
git add resources/views/components/cliente/usuario/pedidos.blade.php
git commit -m "feat(pedidos): comportamento responsivo (lista solo no mobile, detalhe com voltar)"
```

---

## Fora de escopo (anotado, não implementar)

1. **`CompraController::finalizar`** ainda não cria `Venda` no banco — é um stub. O hook "set status='pago' ao finalizar" do design não tem onde plugar agora. Quando a criação de Venda for implementada, basta passar `'status' => Venda::STATUS_PAGO` no `create()`. Spec já cobre.
2. **Snapshot do endereço na `Venda`**: hoje a página não mostra endereço do pedido (decisão do spec — mostrar endereço atual seria errado). Migration futura adiciona colunas de snapshot.
3. **Coluna `forma_pagamento`**: hardcoded "PIX" no componente de detalhe.
4. **Painel admin de mudar status**: a coluna está disponível, mas a UI admin é outro spec.
5. **`text-indigo-500` espalhado** em outras views: cleanup amplo, fora de escopo deste trabalho.

---

## Self-Review

**Spec coverage check:**
- Rota dedicada `/conta/pedidos` → Task 4 ✓
- Rota detalhe `/conta/pedidos/{id}` → Task 5 ✓
- Migration status com default `pendente` → Task 1 ✓
- 5 valores de status como constantes → Task 1 ✓
- Hook `status=pago` no finalizar → **explicitamente removido como fora de escopo** (controller é stub, não cria Venda). Anotado na seção "Fora de escopo".
- Segurança via Eloquent scoping → Task 5 (test_cliente_recebe_404_ao_acessar_pedido_de_outro_cliente)
- 4 components Blade (page, pedidos, pedido-card, pedido-detalhe, status-badge) → Tasks 3 + 6 ✓
- Mestre-detalhe layout → Task 6 ✓
- Status badge com cores corretas → Task 3 ✓
- Empty state com CTA → Task 7 ✓
- Mobile responsivo → Task 9 ✓
- Cleanup `bg-amber-400` → Task 8 ✓
- Link "Meus Pedidos" no `/conta` → Task 8 ✓
- Sem endereço no detalhe (decisão do spec) → Task 6 (não inclui address block) ✓
- "PIX" hardcoded → Task 6 ✓ + anotado em "Fora de escopo"
- Paleta brand formalizada → presente em todas tasks via classes corretas

**Type/naming consistency check:**
- `status` (lowercase string) usado consistentemente em migration, model, factory, badge, testes.
- `pedido` como variável e route param em todos lugares (não confundir com `venda` do domínio — UI fala "pedido", DB fala "venda", relação `auth()->user()->vendas()` é explícita).
- Rotas nomeadas: `usuario.pedidos`, `usuario.pedidos.detalhe` — usadas com `route()` em todos componentes.
- Componentes Blade: namespace `cliente.usuario.*` segue convenção existente do projeto.
