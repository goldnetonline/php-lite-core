<?php

declare (strict_types = 1);

use Goldnetonline\PhpLiteCore\App;
use PHPUnit\Framework\Attributes\RunInSeparateProcess;
use PHPUnit\Framework\TestCase;

final class AppPathConfigTest extends TestCase
{
    #[RunInSeparateProcess]
    public function testStaticPageDirUsesConfigurableViewPaths(): void
    {
        $baseDir = sys_get_temp_dir() . '/php_lite_path_' . uniqid('', true);
        $customViews = $baseDir . '/templates';
        mkdir($customViews . '/pages', 0777, true);

        $configPath = $baseDir . '/config.php';
        file_put_contents(
            $configPath,
            "<?php return ['app' => ['debug' => false, 'route_file' => '$baseDir/routes.php'], 'view' => ['view_dir' => '$customViews', 'pages_dir' => 'pages', 'homepage' => 'home.html', 'global_context' => [], 'cache_dir' => '$baseDir/.cache/view'], 'mail' => ['driver' => 'mailgun', 'mailgun' => ['api_key' => 'x', 'domain_name' => 'x', 'mail_from' => 'x'], 'default_from' => 'x']];"
        );

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
            'internal_view_dir' => $customViews,
        ]);

        self::assertSame($customViews . '/pages', $app->getStaticPageDir());
        self::assertSame('home.html', $app->getHomeStaticPage());
    }
}
