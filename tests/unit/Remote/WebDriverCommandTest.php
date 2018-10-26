<?php

namespace Facebook\WebDriver\Remote;

use Facebook\WebDriver\Exception\WebDriverException;
use PHPUnit\Framework\TestCase;

class WebDriverCommandTest extends TestCase
{
    public function testShouldSetOptionsUsingConstructor()
    {
        $command = new WebDriverCommand('session-id-123', 'bar-baz-name', ['foo' => 'bar']);

        $this->assertSame('session-id-123', $command->getSessionID());
        $this->assertSame('bar-baz-name', $command->getName());
        $this->assertSame(['foo' => 'bar'], $command->getParameters());
    }

    public function testCustomCommandInit()
    {
        $command = new WebDriverCommand('session-id-123', 'customCommand', ['foo' => 'bar']);
        $command->setCustomRequestParameters('/some-url', 'POST');

        $this->assertSame('/some-url', $command->getCustomUrl());
        $this->assertSame('POST', $command->getCustomMethod());
    }

    public function testCustomCommandInvalidUrlExceptionInit()
    {
        $this->expectException(WebDriverException::class);
        $command = new WebDriverCommand('session-id-123', 'customCommand', ['foo' => 'bar']);
        $command->setCustomRequestParameters('url-without-leading-slash', 'POST');
    }

    public function testCustomCommandInvalidMethodExceptionInit()
    {
        $this->expectException(WebDriverException::class);
        $command = new WebDriverCommand('session-id-123', 'customCommand', ['foo' => 'bar']);
        $command->setCustomRequestParameters('/some-url', 'invalid-method');
    }
}
