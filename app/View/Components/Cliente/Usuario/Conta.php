<?php

namespace App\View\Components\Cliente\Usuario;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Conta extends Component
{
    public $pedidos;
    public $endereco;
    public $categorias;
    public $usuario;

    /**
     * Create a new component instance.
     */
    public function __construct($pedidos, $endereco, $categorias, $usuario)
    {
        $this->pedidos = $pedidos;
        $this->endereco = $endereco;
        $this->categorias = $categorias;
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
