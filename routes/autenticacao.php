<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AutenticacaoController;

Route::get('/login', [AutenticacaoController::class, 'login']);
Route::post('/login', [AutenticacaoController::class, 'autenticar']);

Route::get('/cadastro', [AutenticacaoController::class, 'cadastro']);
Route::post('/cadastro', [AutenticacaoController::class, 'registrar']);

Route::post('/logout', [AutenticacaoController::class, 'logout']);