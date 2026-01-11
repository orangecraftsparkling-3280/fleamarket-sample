<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->string('name');               // 商品名
            $table->integer('price');              // 価格
            $table->string('brand')->nullable();   // ブランド名（空でもOK）
            $table->text('description');           // 商品説明
            $table->string('image_url');           // 画像URL
            $table->string('condition');           // 商品の状態（良好、傷あり等）
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('items');
    }
}
