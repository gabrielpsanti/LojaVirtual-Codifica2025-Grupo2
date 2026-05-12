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
