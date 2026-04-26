<?php
//
//namespace App\Http\Controllers\Admin;
//
//
//use App\Http\Controllers\Controller;
//use Illuminate\Http\RedirectResponse;
//use Illuminate\Http\Request;
//use Illuminate\Models\Usuario;
//use Illuminate\Support\Facades\Auth;
//
//class AdminController extends Controller
//{
//    public function index()
//    {
//        return view('admin.index');
//    }
//
//    //aba de registrar
//    public function create()
//    {
//        return view('admin.create');
//    }
//
//    public function store (Request $request): RedirectResponse
//    {
//        $credenciais = $request->validate([
//            'email'=>['requered', 'email'],
//            'password'=>['required'],
//        ]);
//
//        if (Auth::attempt($credenciais)) {
//            $request ->session()->regenarate();
//
//            return to_route('admin.dashboard');
//        }
//
//        //essa estrutura não esta funcionando, tenho que testar
//        //colocar o redirect() ou usar o old()
//        return back()->withErros([
//            'email' =>'Email ou senha inválidos',
//        ])->onlyInput('email');
//
//    }
//
//    public function logout()
//    {
//        Auth::logout();
//
//        return to_route('login');
//    }
//
//}
