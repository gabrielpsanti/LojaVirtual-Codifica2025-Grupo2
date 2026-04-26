<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Desconto extends Model
{
    use SoftDeletes;

    protected $table = 'descontos';

    // Relacionamento: Um desconto pode aparecer em vários produtos
    public function produtos()
    {
        return $this->hasMany(Produto::class, 'desconto_id');
    }

    // Relacionamento: Um desconto pode aparecer em várias vendas
    public function vendas()
    {
        return $this->hasMany(Venda::class, 'desconto_id');
    }

    // Relacionamento: Um desconto pode aparecer em vários itens de venda (produtoVenda)
    public function produtosVenda()
    {
        return $this->hasMany(ProdutoVenda::class, 'desconto_id');
    }
}
