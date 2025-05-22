<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use App\Http\Controllers\Auth\VerifyEmailController;
use App\Http\Controllers\AddressController;
use App\Http\Controllers\ProductController;
use App\Models\Category;
use App\Http\Controllers\FavoriteController;

Route::get('/', [ProductController::class, 'index'])->name('home');
Route::get('/products', [ProductController::class, 'index'])->name('products.index');  // 追加（検索フォームの送信先）
Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show');
Route::get('/api/categories/{parent}/children', function ($parentId) {
    $children = Category::where('parent_id', $parentId)->get();
    return response()->json($children);
});

Route::get('/email/verify', function () {
    return view('auth.verify-email');
})->middleware('auth')->name('verification.notice');

Route::get('/email/verify/{id}/{hash}', VerifyEmailController::class)
    ->middleware(['auth', 'signed', 'throttle:6,1'])
    ->name('verification.verify');

Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();
    return back()->with('message', '確認リンクを再送信しました。');
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/mypage', [ProfileController::class, 'showMypage'])->name('profile.mypage');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::get('/addresses/create', [AddressController::class, 'create'])->name('addresses.create');
    Route::get('/addresses/{address}/edit', [AddressController::class, 'edit'])->name('addresses.edit');
    Route::post('/addresses', [AddressController::class, 'store'])->name('addresses.store');
    Route::patch('/addresses/{address}', [AddressController::class, 'update'])->name('addresses.update');
    Route::delete('/addresses/{address}', [AddressController::class, 'destroy'])->name('addresses.destroy');
    Route::post('/products/{product}/favorite', [FavoriteController::class, 'store'])->name('favorites.store');
    Route::delete('/products/{product}/favorite', [FavoriteController::class, 'destroy'])->name('favorites.destroy');
});

require __DIR__.'/auth.php';
