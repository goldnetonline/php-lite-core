<?php

declare (strict_types = 1);

use Goldnetonline\PhpLiteCore\App;
use PHPUnit\Framework\TestCase;

final class AppConfigTest extends TestCase
{
    public function testAppReadsExternalConfigPath(): void
    {
        $baseDir = sys_get_temp_dir() . '/php_lite_app_' . uniqid('', true);
        mkdir($baseDir, 0777, true);

        $configPath = $baseDir . '/config.php';
        file_put_contents($configPath, "<?php return ['app' => ['debug' => false], 'view' => ['view_dir' => '$baseDir/views', 'pages_dir' => 'pages', 'global_context' => []]];");

        if (!defined('BASE_DIR')) {
            define('BASE_DIR', $baseDir);
        }

        $_SERVER['REQUEST_URI'] = '/';
        $_SERVER['HTTP_HOST'] = 'example.test';
        $_SERVER['SERVER_PORT'] = '80';
        $_SERVER['SERVER_ADDR'] = '127.0.0.1';
        $_SERVER['REMOTE_ADDR'] = '127.0.0.1';
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $_SERVER['QUERY_STRING'] = '';

        $app = new App([
            'base_dir' => $baseDir,
            'config_path' => $configPath,
        ]);

        self::assertFalse($app->getConfig('app.debug', true));
    }
}
