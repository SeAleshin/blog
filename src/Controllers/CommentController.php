<?php

namespace Blog\App\Controllers;

use Blog\App\Database\Connect;
use Blog\App\Models\Comment;
use Blog\App\Models\User;

class CommentController extends Controller
{
    public static function createComment(object $article): void
    {
        var_dump($article['id']);

        $attr['comment'] = trim(htmlspecialchars($_POST['body']));
        $attr['article_id'] = $article['id'];

        parent::setConnect();
        $user = User::where('email', $_SESSION['email'])->pluck('id');
        foreach ($user as $value) {
            $attr['user_id'] = $value;
        }

        Comment::create($attr);

        header('Location: /article/' . $article['slug']);
    }

    public static function showComments()
    {

    }

    public static function deleteComment(): void
    {

    }
}