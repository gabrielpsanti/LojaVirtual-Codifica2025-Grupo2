<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Usuario extends Model
{
    // Relacionamento: Um usuário pode ter muitas vendas
    public function vendas()
    {
        return $this->hasMany(Venda::class, 'cliente_id');
    }
}
