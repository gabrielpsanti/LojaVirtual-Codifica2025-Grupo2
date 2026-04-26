<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Endereco extends Model
{
    use SoftDeletes;

    protected $table = 'enderecos';

    // Relacionamento: Um endereço pertence a um usuário
    public function cliente()
    {
        return $this->belongsTo(Usuario::class, 'usuario_id');
    }
}
