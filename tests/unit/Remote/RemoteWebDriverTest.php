<?php

namespace Facebook\WebDriver\Remote;

use Facebook\WebDriver\Exception\UnknownErrorException;
use Facebook\WebDriver\Interactions\WebDriverActions;
use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\WebDriverNavigation;
use Facebook\WebDriver\WebDriverOptions;
use Facebook\WebDriver\WebDriverWait;
use PHPUnit\Framework\TestCase;

/**
 * Unit part of RemoteWebDriver tests. Ie. tests for behavior which do not interact with the real remote server.
 *
 * @coversDefaultClass \Facebook\WebDriver\Remote\RemoteWebDriver
 */
class RemoteWebDriverTest extends TestCase
{
    /** @var RemoteWebDriver */
    private $driver;

    protected function setUp(): void
    {
        $this->driver = RemoteWebDriver::createBySessionID('session-id', 'http://foo.bar:4444');
    }

    /**
     * @covers ::manage
     */
    public function testShouldCreateWebDriverOptionsInstance()
    {
        $wait = $this->driver->manage();

        $this->assertInstanceOf(WebDriverOptions::class, $wait);
    }

    /**
     * @covers ::navigate
     */
    public function testShouldCreateWebDriverNavigationInstance()
    {
        $wait = $this->driver->navigate();

        $this->assertInstanceOf(WebDriverNavigation::class, $wait);
    }

    /**
     * @covers ::switchTo
     */
    public function testShouldCreateRemoteTargetLocatorInstance()
    {
        $wait = $this->driver->switchTo();

        $this->assertInstanceOf(RemoteTargetLocator::class, $wait);
    }

    /**
     * @covers ::getMouse
     */
    public function testShouldCreateRemoteMouseInstance()
    {
        $wait = $this->driver->getMouse();

        $this->assertInstanceOf(RemoteMouse::class, $wait);
    }

    /**
     * @covers ::getKeyboard
     */
    public function testShouldCreateRemoteKeyboardInstance()
    {
        $wait = $this->driver->getKeyboard();

        $this->assertInstanceOf(RemoteKeyboard::class, $wait);
    }

    /**
     * @covers ::getTouch
     */
    public function testShouldCreateRemoteTouchScreenInstance()
    {
        $wait = $this->driver->getTouch();

        $this->assertInstanceOf(RemoteTouchScreen::class, $wait);
    }

    /**
     * @covers ::action
     */
    public function testShouldCreateWebDriverActionsInstance()
    {
        $wait = $this->driver->action();

        $this->assertInstanceOf(WebDriverActions::class, $wait);
    }

    /**
     * @covers ::wait
     */
    public function testShouldCreateWebDriverWaitInstance()
    {
        $wait = $this->driver->wait(15, 1337);

        $this->assertInstanceOf(WebDriverWait::class, $wait);
    }

    /**
     * @param string $method
     * @param string $expectedExceptionMessage
     * @dataProvider provideMethods
     */
    public function testShouldThrowExceptionOnUnexpectedNullValueFromRemoteEnd($method, $expectedExceptionMessage)
    {
        $executorMock = $this->createMock(HttpCommandExecutor::class);
        $executorMock->expects($this->once())
            ->method('execute')
            ->with($this->isInstanceOf(WebDriverCommand::class))
            ->willReturn(new WebDriverResponse('session-id'));

        $this->driver->setCommandExecutor($executorMock);

        $this->expectException(UnknownErrorException::class);
        $this->expectExceptionMessage($expectedExceptionMessage);
        call_user_func([$this->driver, $method], $this->createMock(WebDriverBy::class));
    }

    public function provideMethods()
    {
        return [
            ['findElement', 'Unexpected server response to findElement command'],
            ['findElements', 'Unexpected server response to findElements command'],
        ];
    }
}
