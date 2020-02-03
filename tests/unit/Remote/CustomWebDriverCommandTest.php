<?php

namespace Facebook\WebDriver\Remote;

use Facebook\WebDriver\Exception\WebDriverException;
use PHPUnit\Framework\TestCase;

class CustomWebDriverCommandTest extends TestCase
{
    public function testShouldSetOptionsUsingConstructor()
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

    public function testCustomCommandInvalidUrlExceptionInit()
    {
        $this->expectException(WebDriverException::class);
        $this->expectExceptionMessage('URL of custom command has to start with / but is "url-without-leading-slash"');

        new CustomWebDriverCommand('session-id-123', 'url-without-leading-slash', 'POST', []);
    }

    public function testCustomCommandInvalidMethodExceptionInit()
    {
        $this->expectException(WebDriverException::class);
        $this->expectExceptionMessage('Invalid custom method "invalid-method", must be one of [GET, POST]');

        new CustomWebDriverCommand('session-id-123', '/some-url', 'invalid-method', []);
    }
}
