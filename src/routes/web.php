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
use App\Http\Controllers\CommentController;
use App\Http\Controllers\MypageController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\FollowController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\TransactionController;

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
    // プロフィール編集
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');

    // 住所関連
    Route::get('/addresses', [AddressController::class, 'index'])->name('addresses.index');
    Route::get('/addresses/create', [AddressController::class, 'create'])->name('addresses.create');
    Route::post('/addresses', [AddressController::class, 'store'])->name('addresses.store');
    Route::get('/addresses/{address}/edit', [AddressController::class, 'edit'])->name('addresses.edit');
    Route::patch('/addresses/{address}', [AddressController::class, 'update'])->name('addresses.update');
    Route::delete('/addresses/{address}', [AddressController::class, 'destroy'])->name('addresses.destroy');

    // お気に入り・コメント
    Route::post('/products/{product}/favorite', [FavoriteController::class, 'store'])->name('favorites.store');
    Route::delete('/products/{product}/favorite', [FavoriteController::class, 'destroy'])->name('favorites.destroy');
    Route::post('/products/{product}/comments', [CommentController::class, 'store'])->name('comments.store');

    // マイページ関連
    Route::get('/mypage', [MypageController::class, 'index'])->name('mypage');

    // 通知
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::patch('/notifications/{notification}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');

    // フォロー
    Route::post('/users/{user}/follow', [FollowController::class, 'toggleFollow'])->name('users.follow.toggle');

    // 商品作成
    Route::get('/products/create', [ProductController::class, 'create'])->name('products.create');
    Route::post('/products', [ProductController::class, 'store'])->name('products.store');

    // 購入関連
    Route::get('/products/{product}/purchase', [PurchaseController::class, 'create'])->name('purchase.create');
    Route::post('/products/{product}/purchase', [PurchaseController::class, 'payment'])->name('purchase.payment');
    Route::post('/products/{product}/checkout', [PurchaseController::class, 'checkout'])->name('purchase.checkout');  // Stripe専用
    Route::get('/purchase/success', [PurchaseController::class, 'success'])->name('purchase.success');
    Route::get('/receipt/{id}', [PurchaseController::class, 'receipt'])->name('receipt.show');

    // 取引画面・ステータス更新
    Route::get('/transactions/{purchase}', [TransactionController::class, 'show'])->name('transactions.show');
    Route::put('/transactions/{purchase}/status', [TransactionController::class, 'updateStatus'])->name('transactions.updateStatus');
});

Route::get('/', [ProductController::class, 'index'])->name('home');
Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show');

// カテゴリAPI
Route::get('/api/categories/{parent}/children', function ($parentId) {
    $children = Category::where('parent_id', $parentId)->get();
    return response()->json($children);
});

// ユーザー詳細
Route::get('/users/{user}', [UserController::class, 'show'])->name('users.show');

require __DIR__.'/auth.php';
