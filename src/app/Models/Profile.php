<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Profile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'img_path',
        'post_code',
        'address',
        'building',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getAvatarUrl()
    {
        if (!$this->img_path) {
            return asset('images/default-user.png');
        }
        return str_starts_with($this->img_path, 'http')
            ? $this->img_path
            : asset('storage/' . $this->img_path);
    }
}
