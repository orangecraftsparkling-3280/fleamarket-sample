<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Item; // Itemモデルをインポート

class Brand extends Model
{
    use HasFactory;

    /**
     * 複数代入を許可する属性
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
    ];

    /**
     * このブランドに属する商品一覧を取得（1対多）
     */
    public function items()
    {
        return $this->hasMany(Item::class);
    }
}
