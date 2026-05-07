<?php

namespace App\View\Components\Cliente\Produtos;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use App\Models\Produto;

class Show extends Component
{
    public $produto;

    /**
     * Create a new component instance.
     */
    public function __construct(Produto $produto)
    {
        $this->produto = $produto;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.cliente.produtos.show');
    }
}
