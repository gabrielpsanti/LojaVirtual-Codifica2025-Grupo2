<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Produto extends Model
{
    protected $fillable = [
        'nome',
        'preco',
        'categoria',
        'descricao',
        'estoque',
        'imagem'
    ];

    // Relacionamento: Um produto pode aparecer em muitas vendas
    public function vendas()
    {
        return $this->hasMany(VendaProduto::class);
    }
}
