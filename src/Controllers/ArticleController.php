<?php

namespace Blog\App\Controllers;

use Blog\App\App\View;
use Blog\App\Models\Post;
use Blog\App\Models\User;
use Blog\App\Notification\Mail;

class ArticleController extends Controller
{
    /**
     * @return void
     */

    public static function getAllArticles(): void
    {
        parent::setConnect();
        $result = Post::all();

        View::loadTemplate('article/articles.twig', 'articles', $result);
    }

    /**
     * @return void
     */

    public static function getArticle(): void
    {
        $slug = substr(htmlspecialchars(key($_GET)), strlen('article/'));
        parent::setConnect();
        $result = Post::with('comments')->where('slug', $slug)->get();

        if (isset($_POST['createComment'])) {
            foreach ($result as $article)
            {
                CommentController::createComment($article);
            }
        }

        if (empty($result)) {
            View::loadError('404.twig', 'Страница не найдена');
            die();
        }

        View::loadTemplate('article/article.twig', 'articles', $result);
    }

    public static function getLastArticles(): void
    {
        parent::setConnect();
        $result['createdAt'] = Post::orderBy('created_at', 'desc')->limit(3)->get();
        $result['updated_at'] = Post::where('updated_at', '!=', 'created_at')->orderBy('updated_at', 'desc')->limit(3)->get();

        View::loadTemplate('main.twig', 'articles', $result);
    }

    public static function createArticle(): void
    {
        if ($_SESSION['auth'] != true) {
            header('Location: /login');
        }

        if (isset($_POST['createArticle'])) {
            self::storeArticle();
            die();
        }

        View::loadTemplate('article/create.twig');
    }

    public static function storeArticle(): void
    {
        $title = trim(htmlspecialchars($_POST['title']));
        $attr['slug'] = str_replace(' ', '_', $title);
        $attr['description'] = ucfirst(trim(htmlspecialchars($_POST['description'])));
        $attr['body'] = ucfirst(trim(htmlspecialchars($_POST['body'])));
        $attr['title'] = ucfirst($title);

        parent::setConnect();
        $user = (new User())->getCurrentUser();
        $attr['user_id'] = $user->id;
        $name = $user->name;

        Post::create($attr);

        Mail::send($_SESSION['email'],
            'Статья - ' . $attr['title'] . ' была создана!',
            'Новая статья',
            ucfirst($name)
        );

        header('Location: /article/' . $attr['slug']);
    }


    public static function editArticle(): void
    {
        if ($_SESSION['auth'] != true) {
            header('Location: /login');
        }

        if (isset($_POST['updateArticle'])) {
            self::updateArticle();
            die();
        }

        $slug = substr(htmlspecialchars(key($_GET)), strlen('article/'));
        $slug = substr($slug, 0, -5);

        parent::setConnect();
        $result = Post::where('slug', $slug)->get();

        View::loadTemplate('article/edit.twig', 'article', $result);
    }

    public static function updateArticle(): void
    {
        $id = htmlspecialchars($_POST['id']);
        $title = trim(htmlspecialchars($_POST['title']));
        $attr['slug'] = str_replace(' ', '_', $title);
        $attr['description'] = ucfirst(trim(htmlspecialchars($_POST['description'])));
        $attr['body'] = ucfirst(trim(htmlspecialchars($_POST['body'])));
        $attr['title'] = ucfirst($title);

        parent::setConnect();
        $article = Post::where('id', $id)->first();
        $article->update($attr);

        $user = (new User())->getCurrentUser();
        Mail::send($user->email,
            'Статья - ' . $attr['title'] . ' была изменена!',
            'Изменение статьи',
            ucfirst($user->name)
        );

        header('Location: /article/' . $attr['slug']);
    }

    public static function deleteArticle(): void
    {
        $id = htmlspecialchars($_POST['id']);

        parent::setConnect();
        $article = Post::where('id', $id)->first();
        $article->delete();

        header('Location: /articles');
    }
}