<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProdutoController;
use App\Http\Controllers\AutenticacaoController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\CategoriaController;
// ADMIN
use App\Http\Controllers\Admin\CategoriaController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\DescontoController;
use App\Http\Controllers\Admin\ProdutoController as ProdutoAdminController;
use App\Http\Controllers\Admin\VendaController;
// CLIENTE
use App\Http\Controllers\Cliente\CompraController;
use App\Http\Controllers\Cliente\EnderecoController;
use App\Http\Controllers\Cliente\LoginController;
use App\Http\Controllers\Cliente\ProdutoController as ProdutoClienteController;
use App\Http\Controllers\Cliente\UsuarioController;

// ROTAS LOGIN, AUTENTICAÇÃO E REGISTRO CLIENTES (E ADMIN)

Route::get('/login', [LoginController::class, 'index'])->name('login');
Route::post('/login/autenticacao', [LoginController::class, 'login'])->name('signin');

Route::get('/cadastro', [LoginController::class, 'create'])->name('cadastro');
Route::post('/cadastro/registro', [LoginController::class, 'store'])->name('cadastro.salvar');

Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// ROTAS CLIENTES

//Route::get('/', function () {
//    return to_route('produtos.index');
//});

Route::get('/', [ProdutoClienteController::class, 'index'])->name('index');

Route::get('/contato', [ProdutoClienteController::class, 'contato'])->name('index.contato');
Route::get('/trocas-e-devolucoes', [ProdutoClienteController::class, 'trocasDevolucoes'])->name('index.trocas-devolucoes');
Route::get('/sobre-nos', [ProdutoClienteController::class, 'sobreNos'])->name('index.sobre-nos');

Route::get('/produtos', [ProdutoClienteController::class, 'todos'])->name('produtos.todos');
Route::get('/{categoria}', [ProdutoClienteController::class, 'categoria'])->name('produtos.categoria');
Route::get('/produtos/{id}', [ProdutoClienteController::class, 'show'])->name('produtos.detalhes');

Route::get('/conta', [UsuarioController::class, 'index'])->name('usuario.index');

Route::get('/conta/editar', [UsuarioController::class, 'edit'])->name('usuario.editar');
Route::put('/conta', [UsuarioController::class, 'update'])->name('usuario.atualizar');

Route::get('/conta/enderecos', [EnderecoController::class, 'index'])->name('usuario.enderecos');

Route::get('/conta/enderecos/{id}/editar', [EnderecoController::class, 'edit'])->name('usuario.enderecos.editar');
Route::put('/conta/enderecos/{id}', [EnderecoController::class, 'update'])->name('usuario.enderecos.atualizar');

// ROTAS CATEGORIAS

Route::get('/admin/categorias', [CategoriaController::class, 'index'])->name('admin.categorias.index');

Route::get('/admin/categorias/criar', [CategoriaController::class, 'create'])->name('admin.categorias.criar');
Route::post('/admin/categorias', [CategoriaController::class, 'store'])->name('admin.categorias.inserir');

Route::get('/admin/categorias/{id}/editar', [CategoriaController::class, 'edit'])->name('admin.categorias.editar');
Route::put('/admin/categorias/{id}', [CategoriaController::class, 'update'])->name('admin.categorias.atualizar');

Route::delete('/admin/categorias/{id}', [CategoriaController::class, 'destroy'])->name('admin.categorias.deletar');

// ROTAS CLIENTES
Route::get('/checkout', [CompraController::class, 'carrinhoView'])->name('checkout.carrinho.view');
Route::post('/checkout', [CompraController::class, 'carrinho'])->name('checkout.carrinho');

Route::get('/checkout/frete', [CompraController::class, 'freteView'])->name('checkout.frete.view');
Route::post('/checkout/frete', [CompraController::class, 'frete'])->name('checkout.frete');

Route::get('/checkout/pedido', [CompraController::class, 'pedidoView'])->name('checkout.pedido.view');
Route::post('/checkout/pedido', [CompraController::class, 'pedido'])->name('checkout.pedido');

Route::get('/checkout/pagamento', [CompraController::class, 'pagamentoView'])->name('checkout.pagamento.view');
Route::post('/checkout/pagamento', [CompraController::class, 'pagamento'])->name('checkout.pagamento');

// ROTAS ADMIN DASHBOARD (INDEX, PÁGINA INICIAL)

Route::get('/admin/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');

// ROTAS ADMIN CATEGORIAS

Route::get('/admin/categorias/criar', [CategoriaController::class, 'create'])->name('admin.categorias.criar');
Route::post('/admin/categorias', [CategoriaController::class, 'store'])->name('admin.categorias.salvar');

// ROTAS ADMIN PRODUTOS (ESTOQUE)

Route::get('/admin/produtos', [ProdutoAdminController::class, 'index'])->name('admin.produtos.index');

Route::get('/admin/produtos/criar', [ProdutoAdminController::class, 'create'])->name('admin.produtos.criar');
Route::post('/admin/produtos', [ProdutoAdminController::class, 'store'])->name('admin.produtos.salvar');

Route::get('/admin/produtos/{id}/editar', [ProdutoAdminController::class, 'edit'])->name('admin.produtos.editar');
Route::put('/admin/produtos/{id}', [ProdutoAdminController::class, 'update'])->name('admin.produtos.atualizar');

Route::delete('/admin/produtos/{id}', [ProdutoAdminController::class, 'destroy'])->name('admin.produtos.deletar');

// ROTAS ADMIN VENDAS

Route::get('/admin/vendas', [VendaController::class, 'index'])->name('admin.vendas.index');
Route::get('/admin/vendas/{id}', [VendaController::class, 'show'])->name('admin.vendas.detalhes');

Route::get('/admin/vendas/criar', [VendaController::class, 'create'])->name('admin.vendas.criar');
Route::post('/admin/vendas', [VendaController::class, 'store'])->name('admin.vendas.salvar');

Route::get('/admin/vendas/{id}/editar', [VendaController::class, 'edit'])->name('admin.vendas.editar');
Route::put('/admin/vendas/{id}', [VendaController::class, 'update'])->name('admin.vendas.atualizar');

Route::delete('/admin/vendas/{id}', [VendaController::class, 'destroy'])->name('admin.vendas.deletar');

// ROTAS ADMIN DESCONTOS

Route::get('/admin/descontos', [DescontoController::class, 'index'])->name('admin.descontos.index');
Route::get('/admin/descontos/{id}', [DescontoController::class, 'show'])->name('admin.descontos.detalhes');

Route::get('/admin/descontos/criar', [DescontoController::class, 'create'])->name('admin.descontos.criar');
Route::post('/admin/descontos', [DescontoController::class, 'store'])->name('admin.descontos.salvar');

Route::get('/admin/descontos/{id}/editar', [DescontoController::class, 'edit'])->name('admin.descontos.editar');
Route::put('/admin/descontos/{id}', [DescontoController::class, 'update'])->name('admin.descontos.atualizar');

Route::delete('/admin/descontos/{id}', [DescontoController::class, 'destroy'])->name('admin.descontos.deletar');

// TESTE

Route::get('/teste', [\App\Http\Controllers\TesteController::class, 'teste'])->name('teste');
