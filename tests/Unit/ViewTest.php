<?php

declare (strict_types = 1);

use Goldnetonline\PhpLiteCore\View;
use PHPUnit\Framework\TestCase;

final class ViewTest extends TestCase
{
    public function testFindFileResolvesKnownExtensions(): void
    {
        $baseDir = sys_get_temp_dir() . '/php_lite_view_' . uniqid('', true);
        $viewDir = $baseDir . '/views';
        mkdir($viewDir, 0777, true);
        file_put_contents($viewDir . '/home.html', '<h1>Home</h1>');

        $app = new class($baseDir, $viewDir)
        {
            public array $globalContext = [];

            public function __construct(public string $baseDir, private string $viewDir)
            {
            }

            public function getConfig($key, $default = null)
            {
                return match ($key) {
                    'view.cache_dir' => $this->baseDir . '/.cache/view',
                    'view.view_dir' => $this->viewDir,
                    default => $default,
                };
            }
        };

        $view = new View($app);

        self::assertSame($viewDir . '/home.html', $view->findFile($viewDir . '/home'));
        self::assertNull($view->findFile($viewDir . '/missing'));
    }
}
