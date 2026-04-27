<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Categoria extends Model
{
    use SoftDeletes;
  
    protected $table = 'categorias';

    protected $fillable = [
        'nome'
    ];
  
    // Relacionamento: Uma categoria contém vários produtos
    public function produtos()
    {
        return $this->hasMany(Produto::class, 'categoria_id');
    }
}
