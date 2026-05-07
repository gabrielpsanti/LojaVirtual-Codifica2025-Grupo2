<?php

namespace App\Repositories;

use App\Models\Categoria;

class ProdutoClienteRepository
{
    public function categoriaIdPelaRota(string $categoria)
    {
        return Categoria::query()->where('rota', $categoria)->firstOrFail();
    }
}
