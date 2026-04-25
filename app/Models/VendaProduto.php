<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VendaProduto extends Model
{
    // Relacionamento: Um item de venda pertence a uma venda
    public function venda()
    {
        return $this->belongsTo(Venda::class);
    }

    // Relacionamento: Um item de venda pertence a um produto
    public function produto()
    {
        return $this->belongsTo(Produto::class);
    }
}
