<?php

namespace App\View\Components\Cliente\PaginaInicial;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Illuminate\View\Component;

class Promocoes extends Component
{

    /**
     * Create a new component instance.
     */
    public function __construct(
        public Collection|array $produtosPromo
    ) {}

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.cliente.pagina-inicial.promocoes');
    }
}
