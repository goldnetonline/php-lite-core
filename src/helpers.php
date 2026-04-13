<?php
use Goldnetonline\PhpLiteCore\App;

/**
 * Read from env variable
 */
if (!function_exists('env')) {
    function env($key, $default = null)
    {
        $env = $_ENV[$key] ?? $default;
        if (in_array($env, ['true', 'false'])) {
            if ($env === 'true') {
                $env = true;
            } else {
                $env = false;
            }

        }
        return $env;
    }
}

/**
 * Root application instance
 */
if (!function_exists('app')) {
    function app()
    {
        return App::getInstance();
    }
}

/**
 * Root request instance
 */
if (!function_exists('request')) {
    function request()
    {
        return App::getInstance()->request;
    }
}

/**
 * Root response instance
 */
if (!function_exists('response')) {
    function response()
    {
        return App::getInstance()->response;
    }
}

/**
 * Root view instance
 */
if (!function_exists('view')) {
    function view()
    {
        return App::getInstance()->view;
    }
}

/**
 * Read from the config file
 */
if (!function_exists('config')) {
    function config($conf, $default = null)
    {
        return App::getInstance()->getConfig($conf, $default);
    }
}

/**
 * Read from request input or query string
 */
if (!function_exists('input')) {
    function input($key = null, $default = null)
    {
        return App::getInstance()->request->input($key, $default);
    }
}

/**
 * Die and dump
 */
if (!function_exists('dd')) {
    function dd($dump)
    {
        if (is_array($dump) || is_object($dump)) {
            echo json_encode($dump);
        } else {
            echo "<tt><pre>";
            echo @var_export($dump);
            echo "</pre></tt>";
        }

        die();
    }
}
