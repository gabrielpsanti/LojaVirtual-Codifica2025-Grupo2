<?php

namespace App\Http\Controllers\Cliente;

use App\Http\Controllers\Controller;
use App\Models\Categoria;
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
        $categorias = Categoria::all();

        return view('cliente.login.login', compact('categorias'));
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

            if (auth()->user()->eh_admin === true) {
                return redirect()->route('admin.index');
            }

            //definir o nome da view
            return redirect()->route('usuario.index');
        }

        return back()->withErrors([
            'email' => 'Email ou senha inválidos',
        ])->withInput($request->only('email'));

    }

    // aba de registrar
    public function create()
    {
        $categorias = Categoria::all();

        return view('cliente.login.cadastro', compact('categorias'));
    }

    // função para registrar/armazenar um novo usuario
    public function store(Request $request)
    {
        $data = $request->except(['_token']);
        $data['password'] = Hash::make($data['password']);

        $usuario = Usuario::create($data);
        Auth::login($usuario);

        return to_route('usuario.index');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
