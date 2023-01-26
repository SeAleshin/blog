<?php

namespace Blog\App\App;

use Blog\App\Exceptions\RouteException;

class Router
{
    private array $pages = [];

    /**
     * @param string $url
     * @param string $path
     * @param string|null $controller
     * @param string|null $method
     * @return void
     */

    public function addRoute(string $url, string $path, ?string $controller = null, ?string $method = null): void
    {
        $this->pages[$url]['path'] = $path;

        if ($controller != null) {
            $this->pages[$url]['controller'] = $controller;
        }

        if ($method != null) {
            $this->pages[$url]['method'] = $method;
        }
    }

    /**
     * @param string $url
     * @return void
     */

    public function route(string $url): void
    {
        $this->match($url);
        $path = $this->pages[$url]['path'];
        $this->ifControllerExist($url);

        if (RouteException::doesTheFileExist($path)) {
            View::loadTemplate($path);
            die();
        }
    }

    /**
     * @param $url
     * @return void
     */

    public function match($url): void
    {
        if (preg_match('/\/article\/(\w+)$/', $url) && $url != '/article/create') {
            $this->pages[$url] = $this->pages['/article/{slug}'];
            unset($this->pages['/article/{slug}']);
        }

        $edit = substr($url, -4, 4);
        if ($edit == 'edit') {
            $this->pages[$url] = $this->pages['/article/{slug}/edit'];
            unset($this->pages['/article/{slug}/edit']);
        }
    }

    /**
     * @param $url
     * @return void
     */

    public function ifControllerExist($url): void
    {
        if (!empty($this->pages[$url]['controller'])) {
            $controller = $this->pages[$url]['controller'];
            $method = $this->pages[$url]['method'];

            if (isset($_POST['deleteArticle'])) {
                $controller::deleteArticle();
                die();
            }

            $controller::$method();
            die();
        }
    }
}
