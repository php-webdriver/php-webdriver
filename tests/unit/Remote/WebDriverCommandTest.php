<?php declare(strict_types=1);

namespace Facebook\WebDriver\Remote;

use PHPUnit\Framework\TestCase;

class WebDriverCommandTest extends TestCase
{
    public function testShouldSetOptionsUsingConstructor(): void
    {
        $command = new WebDriverCommand('session-id-123', 'bar-baz-name', ['foo' => 'bar']);

        $this->assertSame('session-id-123', $command->getSessionID());
        $this->assertSame('bar-baz-name', $command->getName());
        $this->assertSame(['foo' => 'bar'], $command->getParameters());
    }

    public function testShouldCreateNewSessionCommand(): void
    {
        $command = WebDriverCommand::newSession(['bar' => 'baz']);

        $this->assertNull($command->getSessionID());
        $this->assertSame('newSession', $command->getName());
        $this->assertSame(['bar' => 'baz'], $command->getParameters());
    }
}
