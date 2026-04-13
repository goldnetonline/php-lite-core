<?php

declare (strict_types = 1);

use Goldnetonline\PhpLiteCore\Request;
use Goldnetonline\PhpLiteCore\RouteManager;
use PHPUnit\Framework\TestCase;

final class RouteManagerTest extends TestCase
{
    public function testRouteHandlerResolvesByMethodAndPath(): void
    {
        $routeFile = sys_get_temp_dir() . '/php_lite_routes_' . uniqid('', true) . '.php';
        file_put_contents($routeFile, "<?php return ['post,get|hello' => ['ok' => true]];");

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

        $_SERVER['REQUEST_URI'] = '/hello';
        $_SERVER['HTTP_HOST'] = 'example.test';
        $_SERVER['SERVER_PORT'] = '80';
        $_SERVER['SERVER_ADDR'] = '127.0.0.1';
        $_SERVER['REMOTE_ADDR'] = '127.0.0.1';
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_SERVER['QUERY_STRING'] = '';

        $request = new Request($app);
        $routeManager = new RouteManager($app);

        self::assertSame(['ok' => true], $routeManager->getRouteHandler($request));

        @unlink($routeFile);
    }
}
