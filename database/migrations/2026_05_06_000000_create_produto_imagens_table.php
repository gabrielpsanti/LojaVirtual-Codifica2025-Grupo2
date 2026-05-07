<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('produto_imagens')) {
            Schema::create('produto_imagens', function (Blueprint $table) {
                $table->id();
                $table->foreignId('produto_id')->constrained('produtos')->cascadeOnDelete();
                $table->string('caminho');
                $table->timestamps();
            });
        }

        DB::table('produtos')
            ->whereNotNull('imagem')
            ->where('imagem', '<>', '')
            ->orderBy('id')
            ->get(['id', 'imagem'])
            ->each(function ($produto) {
                $existe = DB::table('produto_imagens')
                    ->where('produto_id', $produto->id)
                    ->where('caminho', $produto->imagem)
                    ->exists();

                if ($existe) {
                    return;
                }

                DB::table('produto_imagens')->insert([
                    'produto_id' => $produto->id,
                    'caminho' => $produto->imagem,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            });
    }

    public function down(): void
    {
        Schema::dropIfExists('produto_imagens');
    }
};
