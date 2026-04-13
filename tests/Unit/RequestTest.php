<?php

declare (strict_types = 1);

use Goldnetonline\PhpLiteCore\Request;
use PHPUnit\Framework\TestCase;

final class RequestTest extends TestCase
{
    public function testQueryParsesAndReturnsExpectedValues(): void
    {
        $_SERVER['REQUEST_URI'] = '/contact?name=Temi';
        $_SERVER['HTTP_HOST'] = 'example.test';
        $_SERVER['SERVER_PORT'] = '80';
        $_SERVER['SERVER_ADDR'] = '127.0.0.1';
        $_SERVER['REMOTE_ADDR'] = '127.0.0.1';
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $_SERVER['QUERY_STRING'] = 'name=Temi&channel=web';

        $request = new Request(new stdClass());

        self::assertSame('Temi', $request->query('name'));
        self::assertSame('web', $request->query('channel'));
        self::assertSame('fallback', $request->query('missing', 'fallback'));
    }
}
