<?php

namespace App\Repositories;

class CategoriaRepository
{
    public function nomeDaRota(string $nomeCategoria): string
    {
        $primeiraVersao = strtolower($nomeCategoria);
        $segundaVersao = str_replace(" ", "-", $primeiraVersao);
        return $segundaVersao;
    }

}
