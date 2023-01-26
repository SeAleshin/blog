<?php

namespace Blog\App\Models;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $guarded = [];

    public function comments()
    {
        return $this->hasMany(Comment::class, 'article_id');
    }

    public function owner()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
