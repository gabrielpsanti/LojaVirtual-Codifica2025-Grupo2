<?php

namespace Database\Seeders;

use App\Models\Categoria;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategoriaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categorias = [
            [
                'nome' => 'Blusas e Camisas',
                'rota' => 'blusas-e-camisas',
            ],
            [
                'nome' => 'Casacos e Mangas Longas',
                'rota' => 'casacos-e-mangas-longas',
            ],
            [
                'nome' => 'Bermudas e Calças',
                'rota' => 'bermudas-e-calcas',
            ],
            [
                'nome' => 'Vestidos e Saias',
                'rota' => 'vestidos-e-saias',
            ],
            [
                'nome' => 'Bolsas',
                'rota' => 'bolsas',
            ],
        ];

        foreach ($categorias as $categoria) {
            DB::table('categorias')->insert($categoria);
        }
    }
}
