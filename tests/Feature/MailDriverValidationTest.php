<?php

declare (strict_types = 1);

use Goldnetonline\PhpLiteCore\App;
use Goldnetonline\PhpLiteCore\Exceptions\InvalidMailDriverException;
use Goldnetonline\PhpLiteCore\Mail\Mail;
use PHPUnit\Framework\Attributes\RunInSeparateProcess;
use PHPUnit\Framework\TestCase;

final class MailDriverValidationTest extends TestCase
{
    #[RunInSeparateProcess]
    public function testInvalidDriverThrowsClearException(): void
    {
        $baseDir = sys_get_temp_dir() . '/php_lite_mail_' . uniqid('', true);
        $viewDir = $baseDir . '/views';
        mkdir($viewDir . '/pages', 0777, true);

        $configPath = $baseDir . '/config.php';
        file_put_contents(
            $configPath,
            "<?php return ['app' => ['debug' => false, 'route_file' => '$baseDir/routes.php'], 'view' => ['view_dir' => '$viewDir', 'pages_dir' => 'pages', 'homepage' => 'home.html', 'global_context' => [], 'cache_dir' => '$baseDir/.cache/view'], 'mail' => ['driver' => 'invalid_driver', 'invalid_driver' => [], 'default_from' => 'noreply@example.com']];"
        );

        $_SERVER['REQUEST_URI'] = '/';
        $_SERVER['HTTP_HOST'] = 'example.test';
        $_SERVER['SERVER_PORT'] = '80';
        $_SERVER['SERVER_ADDR'] = '127.0.0.1';
        $_SERVER['REMOTE_ADDR'] = '127.0.0.1';
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $_SERVER['QUERY_STRING'] = '';

        App::getInstance([
            'base_dir' => $baseDir,
            'config_path' => $configPath,
            'internal_view_dir' => $viewDir,
        ]);

        $this->expectException(InvalidMailDriverException::class);
        $mail = new Mail();
        self::assertInstanceOf(Mail::class, $mail);
    }
}
