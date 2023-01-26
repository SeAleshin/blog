<?php

namespace Blog\App\Controllers;

use Blog\App\App\View;
use Blog\App\Models\Post;

class SearchController extends Controller
{
    public static function searchForTitle(): void
    {
        $search = '%' . trim(htmlspecialchars($_POST['search'])) . '%';

        parent::setConnect();
        $result = Post::where('title', 'LIKE', $search)->get();

        View::loadTemplate('/search.twig', 'articles', $result);
    }
}