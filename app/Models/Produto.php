<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Produto extends Model
{
    use SoftDeletes;

    protected $table = 'produtos';

    protected $fillable = [
        'nome',
        'preco',
        'categoria_id',
        'descricao',
        'quantidade',
        'imagem'
    ];

    // Relacionamento: Um produto pertence a uma categoria
    public function categoria()
    {
        return $this->belongsTo(Categoria::class, 'categoria_id');
    }

    // Relacionamento: Um produto pode aparecer em muitas vendas
    public function vendas()
    {
        return $this->hasMany(ProdutoVenda::class, 'produto_id');
    }

    // Relacionamento: Um produto pode conter (pertencer a) um desconto
    public function desconto()
    {
        return $this->belongsTo(Desconto::class, 'desconto_id');
    }
}
