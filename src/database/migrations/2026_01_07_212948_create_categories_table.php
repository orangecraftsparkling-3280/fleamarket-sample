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
        // 1. カテゴリー自体のマスターテーブル
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // カテゴリー名（例：ファッション、家電など）
            $table->timestamps();
        });

        // 2. 商品(items)とカテゴリー(categories)を結びつける中間テーブル
        Schema::create('category_item', function (Blueprint $table) {
            $table->id();
            // foreignIdの引数は紐付け先テーブルの単数形_id
            $table->foreignId('item_id')->constrained()->onDelete('cascade');

            // カテゴリー側のID。constrainedの中身を明示することで不整合を防ぎます
            $table->foreignId('category_id')->constrained('categories')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('category_item');
        Schema::dropIfExists('categories');
    }
};
