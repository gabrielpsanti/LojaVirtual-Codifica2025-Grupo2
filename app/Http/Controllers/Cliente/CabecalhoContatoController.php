<?php

namespace App\Http\Controllers\Cliente;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class CabecalhoContatoController extends Controller
{
    public function contato()
    {
        return view('cliente.cabecalho.contato');
    }

   public function enviar(Request $request)
{
    $dados = $request->validate([
        'nome' => 'required',
        'email' => 'required|email',
        'mensagem' => 'required',
    ]);

    try {

        Mail::raw(
            "Nome: {$dados['nome']}\nEmail: {$dados['email']}\nMensagem: {$dados['mensagem']}",
            function ($message) {
                $message->to('larissaforesticastoldi94669@gmail.com') //caso alguem queira por e-mail para receber mensagens só adcionar aqui
                        ->subject('Nova mensagem do site');
            }
        );

        return back()->with('success', 'Mensagem enviada com sucesso!');
        
    } catch (\Exception $e) {
        dd($e->getMessage());
    }}}