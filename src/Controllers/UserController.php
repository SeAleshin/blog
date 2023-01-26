<?php

namespace Blog\App\Controllers;

use Blog\App\App\View;
use Blog\App\Models\User;
use Blog\App\Notification\RestorePassword;

class UserController extends Controller
{
    public static function createUser(): void
    {
        if (isset($_POST['createUser'])) {
            self::storeUser();
        }

        View::loadTemplate('authentication/registration.twig');
    }

    public static function storeUser(): void
    {
        $attr['name'] = htmlspecialchars(trim($_POST['name']));
        $attr['email'] = htmlspecialchars(trim($_POST['email']));
        $attr['password'] = htmlspecialchars(trim($_POST['password']));
        $attr['password'] = password_hash($attr['password'], PASSWORD_BCRYPT);

        parent::setConnect();
        User::create($attr);

        $_SESSION['auth'] = true;
        $_SESSION['email'] = $attr['email'];

        header('Location: /');
    }

    public static function enterEmailForRestorePassword(): void
    {
        if (isset($_POST['email']) || isset($_POST['code'])) {
            self::restorePassword();
            die();
        }

        if (isset($_POST['changePassword'])) {
            self::changePassword();
            die();
        }

        View::loadTemplate('authentication/enter_email.twig');
    }

    public static function restorePassword(): void
    {
        (isset($_POST['email'])) ?? $_SESSION['email'] = htmlspecialchars(trim($_POST['email']));
        $userCode = htmlspecialchars(trim($_POST['code']));

        var_dump($_POST['code']);

        parent::setConnect();
        $user = User::where('email', $_SESSION['email'])->first();

        if (empty($user)) {
            View::loadTemplate('authentication/restore_password.twig', 'alert', 'Email не найден');
        } else {
            if (isset($_POST['sendCode'])) {
                if ($user->temporary_code == $userCode) {
                    $_SESSION['auth'] = true;

                    $attr['temporary_code'] = '';
                    $user->update($attr);
                    View::loadTemplate('authentication/change_password.twig');
                } else {
                    View::loadTemplate('authentication/restore_password.twig', 'alert', 'Неверный код');
                }
            } else {
                $attr['temporary_code'] = rand(10000, 99999);
                $user->update($attr);
                RestorePassword::send($user->email, $attr['temporary_code'], 'Восстановление пароля', $user->name);
                View::loadTemplate('authentication/restore_password.twig');
            }
        }
    }

    public static function changePassword(): void
    {
        $attr['password'] = htmlspecialchars(trim($_POST['password']));
        $attr['password'] = password_hash($attr['password'], PASSWORD_BCRYPT);

        parent::setConnect();
        $user = User::where('email', $_SESSION['email'])->first();
        $user->update($attr);

        header('Location: /');
    }

    public static function showProfile()
    {
        View::loadTemplate('profile.twig');
    }

    public static function addProfileImg()
    {

    }
}