<?php

namespace App\View\Components\Product;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Info extends Component
{
    public $product;

    public function __construct($product)
    {
        $this->product = $product;
    }

    public function render(): \Illuminate\View\View|Closure|string
    {
        return view('components.product.info');
    }
}