<?php

namespace Facebook\WebDriver\Remote;

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
}
