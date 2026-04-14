<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Models\Usuario;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\RedirectResponse;

//  Criei rotas personiladas para o login.
//  Fiz isso para não usar o nome index ou dashboard mais nas rotas, e de alguma forma
//quebrar o código na hora de redirecionar.

//  Esse nome do controller ainda não fiquei 100% satisfeito com ele, mas deixei por não conseguir
//pensa em um nome melhor

class ClienteController extends Controller
{
    public function index()
    {
        return view('login.index');
    }

    //aba de registrar
    public function create()
    {
        return view('login.create');
    }

    public function store (Request $request): RedirectResponse
    {
        $credenciais = $request->validate([
            'email'=>['requered', 'email'],
            'password'=>['required'],
        ]);

        if (Auth::attempt($credenciais)) {
            $request ->session()->regenarate();

            return to_route('login.dashboard');
        }

        //essa estrutura não esta funcionando, tenho que testar
        //colocar o redirect() ou usar o old()
        return back()->withErros([
            'email' =>'Email ou senha inválidos',
        ])->onlyInput('email');

    }

    public function logout()
    {
        Auth::logout();

        return to_route('login.index');
    }
}
