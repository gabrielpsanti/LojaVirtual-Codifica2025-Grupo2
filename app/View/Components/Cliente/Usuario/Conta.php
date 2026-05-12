<?php

namespace App\View\Components\Cliente\Usuario;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Conta extends Component
{
    public $pedidos;
//    public $endereco;
    public $usuario;

    /**
     * Create a new component instance.
     */
    public function __construct($pedidos, $usuario)
    {
        $this->pedidos = $pedidos;
//        $this->endereco = $endereco;
        $this->usuario = $usuario;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.cliente.usuario.conta');
    }
}
