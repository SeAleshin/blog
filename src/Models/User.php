<?php

namespace Blog\App\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    protected $guarded = [];

    public function comment()
    {
        return $this->hasOne(Comment::class, 'user_id');
    }

    public function article()
    {
        return $this->hasOne(Post::class, 'user_id');
    }

    public function getCurrentUser()
    {
        foreach (User::where('email', $_SESSION['email'])->get() as $value) {
            return $value;
        }
    }


}