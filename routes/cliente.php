<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProdutoController;

Route::get('/admin/produtos', [ProdutoController::class, 'index']);

Route::get('/admin/produtos/criar', [ProdutoController::class, 'create']);
Route::post('/admin/produtos', [ProdutoController::class, 'store']);

Route::get('/admin/produtos/{id}/editar', [ProdutoController::class, 'edit']);
Route::put('/admin/produtos/{id}', [ProdutoController::class, 'update']);

Route::delete('/admin/produtos/{id}', [ProdutoController::class, 'destroy']);