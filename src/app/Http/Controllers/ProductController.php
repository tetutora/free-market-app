<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use Illuminate\Support\Facades\Auth;
use App\Models\History;
use App\Http\Requests\StoreProductRequest;
use App\Models\ProductImage;

class ProductController extends Controller
{
    public function index(Request $request)
{
    $products = Product::with('images') // imagesリレーションをEager Loading
        ->search($request->all())
        ->paginate(20)
        ->withQueryString();

    $categories = Category::whereNull('parent_id')->get();
    $brands = Brand::all();

    return view('products.index', compact('products', 'categories', 'brands'));
}


    public function show(Product $product)
{
    $product->load('images'); // ← 追加！

    $user = Auth::user();

    if ($user) {
        History::updateOrCreate(
            ['user_id' => $user->id, 'product_id' => $product->id],
            ['viewed_at' => now()]
        );
    }

    $product->load('images');

    $otherProducts = Product::where('user_id', $product->user_id)
        ->where('id', '!=', $product->id)
        ->latest()
        ->take(10)
        ->get();

    return view('products.show', compact('product', 'otherProducts'));
}

    public function create()
    {
        $categories = Category::whereNull('parent_id')->get();
        $brands = Brand::all();
        $conditions = [
            '新品・未使用',
            '未使用に近い',
            '目立った傷や汚れなし',
            'やや傷や汚れあり',
            '傷や汚れあり',
            '全体的に状態が悪い',
        ];

        return view('products.create', compact('categories', 'brands', 'conditions'));
    }

    public function store(StoreProductRequest $request)
{
    $validated = $request->validated();

    // まずは商品を作成（category_id, brand_idは削除）
    $product = Product::create([
        'user_id' => Auth::id(),
        'name' => $validated['name'],
        'description' => $validated['description'],
        'price' => $validated['price'],
        'condition' => $validated['condition'],
        'is_listed' => true,
    ]);

    // カテゴリー・ブランドは多対多のためsyncで保存
    $product->categories()->sync($validated['category_ids'] ?? []);
    $product->brands()->sync($validated['brand_ids'] ?? []);

    // 画像アップロード処理
    if ($request->hasFile('images')) {
        foreach ($request->file('images') as $image) {
            $path = $image->store('products', 'public');
            ProductImage::create([
                'product_id' => $product->id,
                'path' => $path,
            ]);
        }
    }

    return redirect()->route('products.index')->with('success', '商品を出品しました。');
}

}
