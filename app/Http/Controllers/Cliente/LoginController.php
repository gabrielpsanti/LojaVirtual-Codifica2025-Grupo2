<?php

namespace App\Http\Controllers\Cliente;

use App\Http\Controllers\Controller;
use App\Models\Usuario;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    // aba de login
    public function index()
    {
        return view('cliente.login.login');
    }

    // autenticação do usuário
    // ADICIONAR ROTEAMENTO DE FOR O ADMIN
    public function login(Request $request): RedirectResponse
    {
        $credenciais = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required']
        ]);

        if (Auth::attempt($credenciais)) {
            $request->session()->regenerate();

            //definir o nome da view
            return view('');
        }

        return back()->withErrors([
            'email' => 'Email ou senha inválidos',
        ])->withInput($request->only('email'));

    }

    // aba de registrar
    public function create()
    {
        return view('cliente.login.cadastro');
    }

    // função para registrar/armazenar um novo usuario
    public function store(Request $request)
    {

        $data = $request->except(['_token']);
        $data['password'] = Hash::make($data['password']);

        $usuario = Usuario::create($data);
        Auth::login($usuario);

        return view('cliente.login.login');
    }

    public function logout()
    {
        Auth::logout();

        return to_route('login.index');
    }
}
