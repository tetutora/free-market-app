<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class FavoriteController extends Controller
{
    public function store(Product $product)
    {
        auth()->user()->favorites()->syncWithoutDetaching([$product->id]);
        return back();
    }

    public function destroy(Product $product)
    {
        auth()->user()->favorites()->detach($product->id);
        return back();
    }
}
