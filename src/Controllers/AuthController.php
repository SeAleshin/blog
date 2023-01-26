<?php

namespace Blog\App\Controllers;

use Blog\App\App\View;
use Blog\App\Models\User;

class AuthController extends Controller
{

    public static function login(): void
    {
        if (isset($_POST['login'])) {
            self::signIn();
        }

        View::loadTemplate('authentication/login.twig');
    }

    public static function signIn(): void
    {
        $login = htmlspecialchars($_POST['email']);
        $password = htmlspecialchars($_POST['password']);

        parent::setConnect();
        $user = User::where('email', $login)->get();
        foreach ($user as $value) {
            if ($value != null && password_verify($password, $value['password'])) {
                $_SESSION['auth'] = true;
                $_SESSION['email'] = $login;

                header('Location: /');
            } else {
                View::loadTemplate('authentication/login.twig', 'failed', 'Неверный логин или пароль');
                die();
            }
        }
    }

    public static function logout(): void
    {
        $_SESSION['auth'] = false;

        header('Location: /');
    }
}