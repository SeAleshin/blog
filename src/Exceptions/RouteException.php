<?php

namespace Blog\App\Exceptions;

use Blog\App\App\View;

class RouteException
{
    /**
     * return true, if file exist, otherwise load template @loadError
     * @param ?string $path
     * @return bool
     */

    public static function doesTheFileExist(?string $path): bool
    {
        $file_dir = 'view/' . $path;

        try {
            if (!file_exists($file_dir) || $path == '') {
                throw new \Exception('Page not found', 404);
            }
        } catch (\Exception $e) {
            View::loadError('404.twig', $e);
            die();
        }
        return true;
    }
}