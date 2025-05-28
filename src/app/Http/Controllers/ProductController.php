<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\StoreProductRequest;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with('images')
            ->search($request->all());

        if ($userId = Auth::id()) {
            $query->where('user_id', '<>', $userId);
        }

        $products = $query->paginate(20)->withQueryString();

        return view('products.index', [
            'products' => $products,
            'categories' => Category::whereNull('parent_id')->get(),
            'brands' => Brand::all(),
        ]);
    }
    public function show(Product $product)
    {
        $product->load('images');

        if ($user = Auth::user()) {
            $product->recordViewHistory($user->id);
        }

        return view('products.show', [
            'product' => $product,
            'otherProducts' => $product->getOtherProductsFromSameUser(),
        ]);
    }

    public function create()
    {
        return view('products.create', $this->loadCommonFormData());
    }

    public function store(StoreProductRequest $request)
    {
        Product::createWithRelations($request, Auth::id());

        return redirect()
            ->route('products.index')
            ->with('success', '商品を出品しました。');
    }

    private function loadCommonFormData(): array
    {
        return [
            'categories' => Category::whereNull('parent_id')->get(),
            'brands' => Brand::all(),
            'conditions' => $this->conditions(),
        ];
    }

    private function conditions(): array
    {
        return [
            '新品・未使用',
            '未使用に近い',
            '目立った傷や汚れなし',
            'やや傷や汚れあり',
            '傷や汚れあり',
            '全体的に状態が悪い',
        ];
    }
}