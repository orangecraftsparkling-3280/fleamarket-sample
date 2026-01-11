<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Item;

class Comment extends Model
{
    use HasFactory;

    /**
     * 複数代入を許可する属性
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'item_id',
        'comment', // blade側で textarea の name を content にしている場合に合わせます
    ];

    /**
     * このコメントを投稿したユーザーを取得（1対多の逆）
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * このコメントが投稿された商品を取得（1対多の逆）
     */
    public function item()
    {
        return $this->belongsTo(Item::class);
    }
}
