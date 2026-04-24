<?php

use App\Http\Controllers\Admin\ProdutoController;
use App\Http\Controllers\Cliente\LoginController;
use Illuminate\Support\Facades\Route;

// ROTAS LOGIN E AUTENTICAÇÃO CLIENTES

Route::get('/login', [LoginController::class, 'login'])->name('login');
Route::post('/login', [LoginController::class, 'autenticar'])->name('autenticar');

Route::get('/cadastro', [LoginController::class, 'cadastro'])->name('cadastro');
Route::post('/cadastro', [LoginController::class, 'registrar'])->name('registrar');

Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

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

// ROTAS CLIENTES

Route::get('/', function () {
    return to_route('produtos.index');
});

Route::get('/produtos', [App\Http\Controllers\Cliente\ProdutoController::class, 'index'])->name('produtos.index');

//Route::get('/produtos/criar', [ProdutoController::class, 'create'])->name('produtos.criar');
Route::post('/produtos', [App\Http\Controllers\Cliente\ProdutoController::class, 'store'])->name('produtos.inserir');

//Route::get('/produtos/{id}/editar', [ProdutoController::class, 'edit'])->name('produtos.editar');
Route::put('/produtos/{id}', [App\Http\Controllers\Cliente\ProdutoController::class, 'update'])->name('produtos.atualizar');

Route::delete('/produtos/{id}', [App\Http\Controllers\Cliente\ProdutoController::class, 'destroy'])->name('produtos.deletar');

// TESTE

Route::get('/teste', [\App\Http\Controllers\TesteController::class, 'teste'])->name('teste');
