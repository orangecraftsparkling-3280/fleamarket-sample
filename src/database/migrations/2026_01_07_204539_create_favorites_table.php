<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('favorites', function (Blueprint $table) {
            $table->id();
            // user_id: 誰が保存したか
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            // item_id: どの商品を保存したか
            $table->foreignId('item_id')->constrained()->onDelete('cascade');
            $table->timestamps();

            // 同じユーザーが同じ商品を二重にお気に入り登録できないようにする制約
            $table->unique(['user_id', 'item_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('favorites');
    }
};
