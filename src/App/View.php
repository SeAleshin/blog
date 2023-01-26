<?php

namespace Blog\App\App;

use Blog\App\Exceptions\RouteException;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class View
{
    /**
     * @return object
     */

    private static function initializeTwig(): object
    {
        $loader = new FilesystemLoader('view');
        return (new Environment($loader));
    }

    /**
     * @param string $path
     * @param string|null $name
     * @param object|array|string|null $values
     * @return void
     */

    public static function loadTemplate(string $path, ?string $name = null, object|array|string|null $values = null): void
    {
        ($_SESSION['auth'] === true) ? $auth = true : $auth = false;

        $twig = self::initializeTwig();
        if (RouteException::doesTheFileExist($path)) {
            if ($values == null) {
                echo $twig->render($path, ['auth' => $auth]);
            } else {
                echo $twig->render($path, [$name => $values, 'auth' => $auth]);
            }
        }
    }

    /**
     * @param string $template
     * @param string|object $e
     * @return void
     */

    public static function loadError(string $template, string|object $e): void
    {
        $twig = self::initializeTwig();
        if (gettype($e) === 'object') {
            echo $twig->render($template, ['message' => $e->getMessage(), 'code' => $e->getCode()]);
            die();
        }

        echo $twig->render($template, ['message' => $e]);
    }
}