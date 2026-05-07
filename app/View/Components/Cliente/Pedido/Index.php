<?php

namespace App\View\Components\Cliente\Pedido;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Index extends Component
{
    public $carrinho;
    public $total;

    /**
     * Create a new component instance.
     */
    public function __construct($carrinho = [], $total = 0)
    {
        $this->carrinho = $carrinho;
        $this->total = $total;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.cliente.pedido.index');
    }
}
