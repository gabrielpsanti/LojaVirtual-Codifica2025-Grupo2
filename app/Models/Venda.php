<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Venda extends Model
{
    use SoftDeletes;

    protected $table = 'vendas';

    // Relacionamento: Uma venda pertence a um cliente (Usuario)
    public function cliente()
    {
        return $this->belongsTo(Usuario::class, 'usuario_id');
    }

    // Relacionamento: Uma venda tem muitos produtos
    public function produtos()
    {
        return $this->hasMany(ProdutoVenda::class, 'venda_id');
    }

    // Relacionamento: Uma venda pode conter (pertencer a) um desconto (cupom)
    public function desconto()
    {
        return $this->belongsTo(Desconto::class, 'desconto_id');
    }
}
