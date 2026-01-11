<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Comment;

class Item extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'price',
        'brand',
        'description',
        'image_url',
        'condition',
    ];

    public function favorites()
    {
        // 中間テーブル 'favorites' を通じて User モデルと紐付け
        return $this->belongsToMany(User::class, 'favorites', 'item_id', 'user_id')->withTimestamps();
    }

    // ブランドとのリレーション（1対多の場合）
    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    // カテゴリーとのリレーション（多対多の場合）
    public function categories()
    {
        return $this->belongsToMany(Category::class, 'category_item');
    }

    // コメントとのリレーション（1対多の場合）
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
}
