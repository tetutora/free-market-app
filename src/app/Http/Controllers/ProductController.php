<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use Illuminate\Support\Facades\Auth;
use App\Models\History;

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
        $user = Auth::user();

        if ($user) {
            History::updateOrCreate(
                ['user_id' => $user->id, 'product_id' => $product->id],
                ['viewed_at' => now()]
            );
        }

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

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|integer|min:1',
            'image' => 'nullable|image|max:2048',
            'category_id' => 'nullable|exists:categories,id',
            'brand_id' => 'nullable|exists:brands,id',
            'condition' => 'required|string|in:新品・未使用,未使用に近い,目立った傷や汚れなし,やや傷や汚れあり,傷や汚れあり,全体的に状態が悪い',
            'is_listed' => 'nullable|boolean',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('products', 'public');
        }

        Product::create([
            'user_id' => Auth::id(),
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'price' => $validated['price'],
            'image_path' => $imagePath,
            'category_id' => $validated['category_id'] ?? null,
            'brand_id' => $validated['brand_id'] ?? null,
            'condition' => $validated['condition'],
            'is_listed' => $request->has('is_listed'),
        ]);

        return redirect()->route('mypage.index')->with('success', '商品を出品しました。');
    }

}
