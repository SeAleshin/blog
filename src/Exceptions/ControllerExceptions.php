<?php

namespace Blog\App\Exceptions;

use Blog\App\Controllers\Controller;

class ControllerExceptions
{
    public static function isCorrectController($controller): bool
    {
        try {
            if ($controller instanceof Controller) {
                return true;
            } else {
                throw new \Exception();
            }
        } catch (\Exception $e) {
            return false;
        }
    }
}