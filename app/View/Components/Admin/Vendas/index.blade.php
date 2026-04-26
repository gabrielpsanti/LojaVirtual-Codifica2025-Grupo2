<?php

namespace App\View\Components\Admin\Vendas;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Cadastro extends Component
{
    public $vendas;
    /**
     * Create a new component instance.
     */
    public function __construct($vendas = null)
    {
        $this->vendas = $vendas;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.admin.vendas.index');
    }
}