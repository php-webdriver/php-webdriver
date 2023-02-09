<?php declare(strict_types=1);

namespace Facebook\WebDriver\Remote;

use Facebook\WebDriver\Exception\Internal\LogicException;
use PHPUnit\Framework\TestCase;

class CustomWebDriverCommandTest extends TestCase
{
    public function testShouldSetOptionsUsingConstructor(): void
    {
        $command = new CustomWebDriverCommand(
            'session-id-123',
            '/some-url',
            'POST',
            ['foo' => 'bar']
        );

        $this->assertSame('/some-url', $command->getCustomUrl());
        $this->assertSame('POST', $command->getCustomMethod());
    }

    public function testCustomCommandInvalidUrlExceptionInit(): void
    {
        $this->expectException(LogicException::class);
        $this->expectExceptionMessage('URL of custom command has to start with / but is "url-without-leading-slash"');

        new CustomWebDriverCommand('session-id-123', 'url-without-leading-slash', 'POST', []);
    }

    public function testCustomCommandInvalidMethodExceptionInit(): void
    {
        $this->expectException(LogicException::class);
        $this->expectExceptionMessage('Invalid custom method "invalid-method", must be one of [GET, POST]');

        new CustomWebDriverCommand('session-id-123', '/some-url', 'invalid-method', []);
    }
}
