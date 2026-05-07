<?php

namespace App\Repositories;

use App\Models\Categoria;

class ProdutoRepository
{
    public function categoriaPelaRota(string $categoria)
    {
        return Categoria::query()->where('rota', $categoria)->firstOrFail();
    }
}
