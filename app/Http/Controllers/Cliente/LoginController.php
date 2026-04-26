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
    public function index()
    {
        return view('cliente.login.login');
    }

    //aba de registrar
    public function create()
    {
        return view('cliente.login.cadastro');
    }

    public function store (Request $request): RedirectResponse
    {
        $credenciais = $request->validate([
            'email'=>['required', 'email'],
            'password'=>['required']
        ]);

        if (Auth::attempt($credenciais)) {
            $request ->session()->regenerate();

            //definir o nome da view
            return view('');
        }

        return back()->withErrors([
            'email' =>'Email ou senha inválidos',
        ])->withInput($request->only('email'));

    }

    //função para registrar um novo usuario
    public function update(Request $request) {

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
