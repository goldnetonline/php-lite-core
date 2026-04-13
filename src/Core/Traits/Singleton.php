<?php
namespace Goldnetonline\PhpLiteCore\Traits;

trait Singleton
{
    private static $instance;

    /**
     * Make this application a single instance app
     * return the main instance of the app
     */
    public static function getInstance(...$arguments)
    {
        if (!Self::$instance) {
            Self::$instance = new Self(...$arguments);
        }

        return Self::$instance;
    }
}
