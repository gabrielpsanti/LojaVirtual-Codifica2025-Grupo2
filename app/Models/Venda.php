<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Venda extends Model
{
    // Relacionamento: Uma venda pertence a um cliente (Usuario)
    public function cliente()
    {
        return $this->belongsTo(Usuario::class, 'cliente_id');
    }

    // Relacionamento: Uma venda tem muitos produtos
    public function produtos()
    {
        return $this->hasMany(VendaProduto::class);
    }
}
