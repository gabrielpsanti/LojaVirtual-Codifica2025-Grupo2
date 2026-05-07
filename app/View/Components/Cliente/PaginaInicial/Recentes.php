<?php

namespace App\View\Components\Cliente\PaginaInicial;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Illuminate\View\Component;

class Recentes extends Component
{
    public Collection|array $produtosRecentes;

    /**
     * Create a new component instance.
     */
    public function __construct(Collection|array $produtosRecentes)
    {
        $this->produtosRecentes = $produtosRecentes;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.cliente.pagina-inicial.recentes');
    }
}
