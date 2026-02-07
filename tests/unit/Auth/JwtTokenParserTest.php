<?php

declare(strict_types=1);

use Neat\Auth\JwtTokenParser;
use PHPUnit\Framework\TestCase;

final class JwtTokenParserTest extends TestCase
{
    public function testParseReturnsClaimsForValidToken(): void
    {
        $parser = new JwtTokenParser();

        $token = 'header.eyJleHAiOjE3NzA0ODAyODgsImlhdCI6MTc3MDQzNzA4OCwibmJmIjoxNzcwNDM2OTA4LCJzdWIiOjEsImdpZCI6MiwidGVuIjoxLCJ0aWQiOjMzOCwic2NvcGUiOiJyZWFkIHdyaXRlIn0.signature';

        $claims = $parser->parse($token);

        $this->assertIsArray($claims);
        $this->assertArrayHasKey('sub', $claims);
        $this->assertSame(1, $claims['sub']);
        $this->assertArrayHasKey('gid', $claims);
        $this->assertSame(2, $claims['gid']);
    }

    public function testParseReturnsEmptyArrayForInvalidToken(): void
    {
        $parser = new JwtTokenParser();

        $this->assertSame([], $parser->parse('invalid.token'));
        $this->assertSame([], $parser->parse(''));
    }
}