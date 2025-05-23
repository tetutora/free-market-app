<?php

namespace App\View\Components\Product;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class CommentIcon extends Component
{
    public $count;

    public function __construct($count)
    {
        $this->count = $count;
    }

    public function render(): \Illuminate\View\View|Closure|string
    {
        return view('components.product.comment-icon');
    }
}