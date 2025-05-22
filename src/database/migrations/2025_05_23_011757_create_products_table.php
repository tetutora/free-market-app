<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // 出品者ID
            $table->string('name'); // 商品名
            $table->text('description')->nullable(); // 説明
            $table->unsignedInteger('price'); // 価格
            $table->string('image_path')->nullable(); // 商品画像
            $table->foreignId('category_id')->nullable()->constrained('categories')->onDelete('set null');
            $table->string('condition'); // 商品状態（例: "新品", "中古" など）
            $table->boolean('is_listed')->default(true); // 出品状況（true: 出品中, false: 非公開）
            $table->foreignId('brand_id')->nullable()->constrained()->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
