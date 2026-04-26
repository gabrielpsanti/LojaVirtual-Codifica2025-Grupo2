<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProdutoController;
use App\Http\Controllers\AutenticacaoController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\CategoriaController;

// ROTAS LOGIN E AUTENTICAÇÃO CLIENTES

Route::get('/login', [AutenticacaoController::class, 'login'])->name('login');
Route::post('/login', [AutenticacaoController::class, 'autenticar'])->name('autenticar');

Route::get('/cadastro', [AutenticacaoController::class, 'cadastro'])->name('cadastro');
Route::post('/cadastro', [AutenticacaoController::class, 'registrar'])->name('registrar');

Route::post('/logout', [AutenticacaoController::class, 'logout'])->name('logout');

// ROTAS LOGIN E AUTENTICAÇÃO ADMIN

// Route::get('/admin/login', [AutenticacaoController::class, 'login'])->name('admin.login');
// Route::post('/admin/login', [AutenticacaoController::class, 'autenticar'])->name('admin.autenticar');

// Route::get('/admin/cadastro', [AutenticacaoController::class, 'cadastro'])->name('admin.cadastro');
// Route::post('/admin/cadastro', [AutenticacaoController::class, 'registrar'])->name('admin.registrar');

// Route::post('/admin/logout', [AutenticacaoController::class, 'logout'])->name('admin.logout');

// ROTAS ADMIN

Route::get('/admin/produtos', [ProdutoController::class, 'index'])->name('admin.produtos.index');

Route::get('/admin/produtos/criar', [ProdutoController::class, 'create'])->name('admin.produtos.criar');
Route::post('/admin/produtos', [ProdutoController::class, 'store'])->name('admin.produtos.inserir');

Route::get('/admin/produtos/{id}/editar', [ProdutoController::class, 'edit'])->name('admin.produtos.editar');
Route::put('/admin/produtos/{id}', [ProdutoController::class, 'update'])->name('admin.produtos.atualizar');

Route::delete('/admin/produtos/{id}', [ProdutoController::class, 'destroy'])->name('admin.produtos.deletar');

// ROTAS CATEGORIAS

Route::get('/admin/categorias', [CategoriaController::class, 'index'])->name('admin.categorias.index');

Route::get('/admin/categorias/criar', [CategoriaController::class, 'create'])->name('admin.categorias.criar');
Route::post('/admin/categorias', [CategoriaController::class, 'store'])->name('admin.categorias.inserir');

Route::get('/admin/categorias/{id}/editar', [CategoriaController::class, 'edit'])->name('admin.categorias.editar');
Route::put('/admin/categorias/{id}', [CategoriaController::class, 'update'])->name('admin.categorias.atualizar');

Route::delete('/admin/categorias/{id}', [CategoriaController::class, 'destroy'])->name('admin.categorias.deletar');

// ROTAS CLIENTES

Route::get('/', function () {
    return to_route('produtos.index');
});

Route::get('/produtos', [ProdutoController::class, 'index'])->name('produtos.index');

//Route::get('/produtos/criar', [ProdutoController::class, 'create'])->name('produtos.criar');
Route::post('/produtos', [ProdutoController::class, 'store'])->name('produtos.inserir');

//Route::get('/produtos/{id}/editar', [ProdutoController::class, 'edit'])->name('produtos.editar');
Route::put('/produtos/{id}', [ProdutoController::class, 'update'])->name('produtos.atualizar');

Route::delete('/produtos/{id}', [ProdutoController::class, 'destroy'])->name('produtos.deletar');

// TESTE

Route::get('/teste', [\App\Http\Controllers\TesteController::class, 'teste'])->name('teste');
