<?php

namespace App\Http\Controllers\Cliente;

use App\Http\Controllers\Controller;
use App\Models\Categoria;
use App\Models\Produto;
use Illuminate\Http\Request;

class CompraController extends Controller
{
    // Exibe a tela do Carrinho de Compras
    public function index()
    {
        $categorias = Categoria::all();

        // 1. Pega o carrinho da sessão
        $itensSessao = session()->get('carrinho', []);

        $carrinho = [];
        $total = 0;

        // 2. Se houver itens, busca os detalhes no banco
        if (!empty($itensSessao)) {
            foreach ($itensSessao as $item) {
                $produto = Produto::find($item['id']);
                if ($produto) {
                    $subtotal = $produto->preco * $item['quantidade'];
                    $total += $subtotal;

                    $carrinho[] = [
                        'id' => $produto->id,
                        'nome' => $produto->nome,
                        'preco' => $produto->preco,
                        'quantidade' => $item['quantidade'],
                        'subtotal' => $subtotal,
                        'imagem' => $produto->imagem,
                        'descricao' => $produto->descricao
                    ];
                }
            }
        }

        return view('cliente.pedido.carrinho', compact('categorias', 'carrinho', 'total'));
    }

    // Exibe a tela de checkout (endereço, pagamento, resumo)
    public function carrinhoView()
    {
        $categorias = Categoria::all();
        $itensSessao = session()->get('carrinho', []);
        $carrinho = [];
        $total = 0;

        foreach ($itensSessao as $item) {
            $produto = Produto::find($item['id']);
            if ($produto) {
                $subtotal = $produto->preco * $item['quantidade'];
                $total += $subtotal;
                $carrinho[] = [
                    'id' => $produto->id,
                    'nome' => $produto->nome,
                    'preco' => $produto->preco,
                    'quantidade' => $item['quantidade'],
                    'subtotal' => $subtotal,
                ];
            }
        }

        return view('cliente.pedido.index', compact('categorias', 'carrinho', 'total'));
    }
    public function finalizar(Request $request)
    {
        // Aqui entraria a lógica de salvar no banco de dados (Venda e ProdutoVenda)
        // Por agora, vamos apenas simular e redirecionar para o sucesso

        $total = $request->input('total', 0);
        session()->put('ultimo_total', $total);

        // Limpa o carrinho
        session()->forget('carrinho');

        return redirect()->route('checkout.sucesso');
    }

    // Exibe a tela de sucesso com QR Code
    public function sucesso()
    {
        $categorias = Categoria::all();
        $total = session()->get('ultimo_total', 0);

        return view('cliente.pedido.sucesso', compact('categorias', 'total'));
    }

    public function carrinho(Request $request)
    {
        // 1. Pega o ID do produto enviado pelo formulário
        $produtoId = $request->input('produto_id');

        // 2. Lógica para adicionar ao carrinho (usando sessão)
        $carrinho = session()->get('carrinho', []);

        // Se o produto já existe, aumenta a quantidade, senão adiciona 1
        if (isset($carrinho[$produtoId])) {
            $carrinho[$produtoId]['quantidade']++;
        } else {
            $carrinho[$produtoId] = [
                "id" => $produtoId,
                "quantidade" => 1
            ];
        }

        session()->put('carrinho', $carrinho);

        // 3. Redirecionar para a rota do carrinho
        return redirect()->route('carrinho.index')->with('success', 'Produto adicionado!');
    }

    public function remover($id)
    {
        $carrinho = session()->get('carrinho', []);

        if (isset($carrinho[$id])) {
            unset($carrinho[$id]);
            session()->put('carrinho', $carrinho);
        }

        return redirect()->route('carrinho.index')->with('success', 'Produto removido!');
    }
}

// // Exibe a tela de escolha de Frete / Endereço de Entrega
// public function freteView()
// {
//     $categorias = Categoria::all();
//     $itensSessao = session()->get('carrinho', []);
//     $total = 0;
//     $carrinho = [];

//     foreach ($itensSessao as $item) {
//         $produto = Produto::find($item['id']);
//         if ($produto) {
//             $subtotal = $produto->preco * $item['quantidade'];
//             $total += $subtotal;
//             $carrinho[] = ['nome' => $produto->nome, 'preco' => $produto->preco, 'quantidade' => $item['quantidade'], 'subtotal' => $subtotal];
//         }
//     }

//     return view('cliente.pedido.index', compact('categorias', 'carrinho', 'total'));
// }

// Exibe a tela de Revisão do Pedido
// public function pedidoView()
// {
//     return $this->freteView(); // Reutiliza a lógica por enquanto
// }

// // Exibe a tela de Pagamento
// public function pagamentoView()
// {
//     return $this->freteView(); // Reutiliza a lógica por enquanto
// }

// Processa a finalização da compra