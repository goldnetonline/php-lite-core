<?php

declare (strict_types = 1);

use Goldnetonline\PhpLiteCore\Response;
use PHPUnit\Framework\TestCase;

final class ResponseTest extends TestCase
{
    public function testMakeWithArrayBuildsJsonResponse(): void
    {
        $response = new Response(new stdClass());
        $response->make(['ok' => true], 201);

        self::assertSame(201, $response->code);
        self::assertSame('{"ok":true}', $response->responseText);
    }

    public function testMakeWithStringBuildsHtmlResponse(): void
    {
        $response = new Response(new stdClass());
        $response->make('<h1>Hello</h1>', 202);

        self::assertSame(202, $response->code);
        self::assertSame('<h1>Hello</h1>', $response->responseText);
    }
}
