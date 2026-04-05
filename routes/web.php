<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

require __DIR__.'/autenticacao.php';
require __DIR__.'/cliente.php';
require __DIR__.'/admin/produtos.php';