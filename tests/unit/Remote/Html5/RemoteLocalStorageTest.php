<?php

namespace Facebook\WebDriver\Remote\Html5;

use Facebook\WebDriver\Remote\DriverCommand;
use Facebook\WebDriver\Remote\RemoteExecuteMethod;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Facebook\WebDriver\Remote\Html5\RemoteLocalStorage
 */
class RemoteLocalStorageTest extends TestCase
{
    /** @var RemoteExecuteMethod|\PHPUnit\Framework\MockObject\MockObject */
    private $executor;

    protected function setUp(): void
    {
        $this->executor = $this->getMockBuilder(RemoteExecuteMethod::class)
            ->disableOriginalConstructor()
            ->getMock();
    }

    public function testShouldClearStorage()
    {
        $this->executor->expects($this->once())
            ->method('execute')
            ->with(DriverCommand::CLEAR_LOCAL_STORAGE);

        $storage = new RemoteLocalStorage($this->executor);

        $storage->clear();
    }

    public function testShouldReturnItemByKeyFromStorage()
    {
        $this->executor->expects($this->once())
            ->method('execute')
            ->with(DriverCommand::GET_LOCAL_STORAGE_ITEM, [':key' => 'thing-one'])
            ->willReturn('value-of-thing-one');

        $storage = new RemoteLocalStorage($this->executor);

        $value = $storage->getItem('thing-one');
        $this->assertSame('value-of-thing-one', $value);
    }

    public function testShouldReturnAllKeysInStorage()
    {
        $this->executor->expects($this->once())
            ->method('execute')
            ->with(DriverCommand::GET_LOCAL_STORAGE_KEYS)
            ->willReturn(['key1', 'key2', 'key3']);

        $storage = new RemoteLocalStorage($this->executor);

        $value = $storage->keySet();
        $this->assertSame(['key1', 'key2', 'key3'], $value);
    }

    public function testShouldRemoveItemFromStorage()
    {
        $this->executor->expects($this->once())
            ->method('execute')
            ->with(DriverCommand::REMOVE_LOCAL_STORAGE_ITEM);

        $storage = new RemoteLocalStorage($this->executor);

        $storage->removeItem('item2');
    }

    public function testShouldSetItemInStorage()
    {
        $this->executor->expects($this->once())
            ->method('execute')
            ->with(DriverCommand::SET_LOCAL_STORAGE_ITEM, ['key' => 'key2', 'value' => 'value2']);

        $storage = new RemoteLocalStorage($this->executor);

        $storage->setItem('key2', 'value2');
    }

    public function testShouldReturnNumberOfItemsInStorage()
    {
        $this->executor->expects($this->once())
            ->method('execute')
            ->with(DriverCommand::GET_LOCAL_STORAGE_SIZE)
            ->willReturn(7);

        $storage = new RemoteLocalStorage($this->executor);

        $size = $storage->size();
        $this->assertSame(7, $size);
    }
}
