<?php
require_once __DIR__ . '/vendor/autoload.php';

session_start();

use Blog\App\App\Router;
use Blog\App\Controllers\ArticleController;
use Blog\App\Controllers\UserController;
use Blog\App\Controllers\AuthController;
use Blog\App\Controllers\SearchController;

$router = new Router();

$router->addRoute('/', 'main.twig', ArticleController::class, 'getLastArticles');
$router->addRoute('/about', 'about.twig');
$router->addRoute('/article/{slug}', 'article/article.twig', ArticleController::class, 'getArticle');
$router->addRoute('/article/create', 'article/create.twig', ArticleController::class, 'createArticle');
$router->addRoute('/articles', 'article/articles.twig', ArticleController::class, 'getAllArticles');
$router->addRoute('/article/{slug}/edit', 'article/edit.twig', ArticleController::class, 'editArticle');
$router->addRoute('/registration', 'authentication/registration.twig', UserController::class, 'createUser');
$router->addRoute('/login', 'authentication/login.twig', AuthController::class, 'login');
$router->addRoute('/logout', 'authentication/login.twig', AuthController::class, 'logout');
$router->addRoute('/search', 'search.twig', SearchController::class, 'searchForTitle');
$router->addRoute('/restore_password', 'enter_email.twig', UserController::class, 'enterEmailForRestorePassword');
$router->addRoute('/profile', 'profile.twig', UserController::class, 'showProfile');

$router->route('/' . key($_GET));
