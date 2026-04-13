<?php

declare (strict_types = 1);

use Goldnetonline\PhpLiteCore\Request;
use Goldnetonline\PhpLiteCore\RouteManager;
use PHPUnit\Framework\TestCase;

final class RouteManagerMethodsTest extends TestCase
{
    public function testOnlyMatchingHttpMethodResolvesHandler(): void
    {
        $routeFile = sys_get_temp_dir() . '/php_lite_routes_method_' . uniqid('', true) . '.php';
        file_put_contents($routeFile, "<?php return ['post|get-only' => ['method' => 'post']];");

        $app = new class($routeFile)
        {
            public function __construct(private string $routeFilePath)
            {
            }

            public function getConfig($key, $default = null)
            {
                if ($key === 'app.route_file') {
                    return $this->routeFilePath;
                }

                return $default;
            }
        };

        $_SERVER['REQUEST_URI'] = '/get-only';
        $_SERVER['HTTP_HOST'] = 'example.test';
        $_SERVER['SERVER_PORT'] = '80';
        $_SERVER['SERVER_ADDR'] = '127.0.0.1';
        $_SERVER['REMOTE_ADDR'] = '127.0.0.1';
        $_SERVER['QUERY_STRING'] = '';

        $_SERVER['REQUEST_METHOD'] = 'GET';
        $getRequest = new Request($app);
        $routeManager = new RouteManager($app);
        self::assertNull($routeManager->getRouteHandler($getRequest));

        $_SERVER['REQUEST_METHOD'] = 'POST';
        $postRequest = new Request($app);
        self::assertSame(['method' => 'post'], $routeManager->getRouteHandler($postRequest));

        @unlink($routeFile);
    }
}
