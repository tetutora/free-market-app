<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $products = Product::search($request->all())->paginate(20)->withQueryString();

        $categories = Category::whereNull('parent_id')->get();
        $brands = Brand::all();

        return view('products.index', compact('products', 'categories', 'brands'));
    }

    public function show(Product $product)
    {
        return view('products.show', compact('product'));
    }
}
