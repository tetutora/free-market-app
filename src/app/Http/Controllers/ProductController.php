<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class ProductController extends Controller
{
    public function index()
    {
        // 出品中の商品だけ取得（必要に応じて条件を調整）
        $products = Product::where('is_listed', true)
            ->with(['brand', 'category', 'user']) // 関連モデルをまとめて取得
            ->paginate(20); // ページネーション（20件ずつ）

        return view('products.index', compact('products'));
    }

    public function show(Product $product)
    {
        return view('products.show', compact('product'));
    }

}
