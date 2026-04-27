<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProdutoVenda extends Model
{
    use SoftDeletes;

    protected $table = 'produtos_venda';

    // Relacionamento: Um item de venda pertence a uma venda
    public function venda()
    {
        return $this->belongsTo(Venda::class, 'venda_id');
    }

    // Relacionamento: Um item de venda pertence a um produto
    public function produto()
    {
        return $this->belongsTo(Produto::class, 'produto_id');
    }

    public function desconto()
    {
        return $this->belongsTo(Desconto::class, 'desconto_id');
    }
}
