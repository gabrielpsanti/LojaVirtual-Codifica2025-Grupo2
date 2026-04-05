<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('venda_produto', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('venda_id');
            $table->unsignedInteger('produto_id');
            $table->integer('quantidade');
            $table->decimal('preco', 8, 2);

            $table->foreign('venda_id')
                ->references('id')
                ->on('vendas');

            $table->foreign('produto_id')
                ->references('id')
                ->on('produtos');
                
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('venda_produto');
    }
};
