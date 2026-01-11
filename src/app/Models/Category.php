<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Item; // Itemモデルをインポート

class Category extends Model
{
    use HasFactory;

    /**
     * 複数代入を許可する属性
     * * @var array<int, string>
     */
    protected $fillable = [
        'name',
    ];

    /**
     * このカテゴリーに属する商品一覧を取得（多対多）
     */
    public function items()
    {
        // 中間テーブル 'category_item' を通じて Item モデルと紐付け
        return $this->belongsToMany(Item::class, 'category_item');
    }
}
