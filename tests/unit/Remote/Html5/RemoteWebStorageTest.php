<?php

namespace Facebook\WebDriver\Remote\Html5;

use Facebook\WebDriver\Html5\LocalStorageInterface;
use Facebook\WebDriver\Html5\SessionStorageInterface;
use Facebook\WebDriver\Remote\RemoteExecuteMethod;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Facebook\WebDriver\Remote\Html5\RemoteWebStorage
 */
class RemoteWebStorageTest extends TestCase
{
    /** @var RemoteExecuteMethod|\PHPUnit\Framework\MockObject\MockObject */
    private $executor;

    protected function setUp(): void
    {
        $this->executor = $this->getMockBuilder(RemoteExecuteMethod::class)
            ->disableOriginalConstructor()
            ->getMock();
    }

    public function testShouldReturnLocalStorage()
    {
        $storage = new RemoteWebStorage($this->executor);

        $local = $storage->getLocalStorage();
        $this->assertInstanceOf(RemoteLocalStorage::class, $local);
        $this->assertInstanceOf(LocalStorageInterface::class, $local);
    }

    public function testShouldReturnSessionStorage()
    {
        $storage = new RemoteWebStorage($this->executor);

        $session = $storage->getSessionStorage();
        $this->assertInstanceOf(RemoteSessionStorage::class, $session);
        $this->assertInstanceOf(SessionStorageInterface::class, $session);
    }
}
