<?php declare(strict_types=1);

namespace Facebook\WebDriver\Remote;

use Facebook\WebDriver\Exception\Internal\UnexpectedResponseException;
use Facebook\WebDriver\Interactions\WebDriverActions;
use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\WebDriverNavigation;
use Facebook\WebDriver\WebDriverOptions;
use Facebook\WebDriver\WebDriverWait;
use PHPUnit\Framework\TestCase;

/**
 * Unit part of RemoteWebDriver tests. I.e. tests for behavior which do not interact with the real remote server.
 *
 * @coversDefaultClass \Facebook\WebDriver\Remote\RemoteWebDriver
 */
class RemoteWebDriverTest extends TestCase
{
    /** @var RemoteWebDriver */
    private $driver;

    protected function setUp(): void
    {
        // `createBySessionID()` is used because it is the simplest way to instantiate real RemoteWebDriver
        $this->driver = RemoteWebDriver::createBySessionID(
            'session-id',
            'http://foo.bar:4444',
            null,
            null,
            true,
            new DesiredCapabilities([])
        );
    }

    /**
     * @covers ::manage
     */
    public function testShouldCreateWebDriverOptionsInstance(): void
    {
        $wait = $this->driver->manage();

        $this->assertInstanceOf(WebDriverOptions::class, $wait);
    }

    /**
     * @covers ::navigate
     */
    public function testShouldCreateWebDriverNavigationInstance(): void
    {
        $wait = $this->driver->navigate();

        $this->assertInstanceOf(WebDriverNavigation::class, $wait);
    }

    /**
     * @covers ::switchTo
     */
    public function testShouldCreateRemoteTargetLocatorInstance(): void
    {
        $wait = $this->driver->switchTo();

        $this->assertInstanceOf(RemoteTargetLocator::class, $wait);
    }

    /**
     * @covers ::getMouse
     */
    public function testShouldCreateRemoteMouseInstance(): void
    {
        $wait = $this->driver->getMouse();

        $this->assertInstanceOf(RemoteMouse::class, $wait);
    }

    /**
     * @covers ::getKeyboard
     */
    public function testShouldCreateRemoteKeyboardInstance(): void
    {
        $wait = $this->driver->getKeyboard();

        $this->assertInstanceOf(RemoteKeyboard::class, $wait);
    }

    /**
     * @covers ::getTouch
     */
    public function testShouldCreateRemoteTouchScreenInstance(): void
    {
        $wait = $this->driver->getTouch();

        $this->assertInstanceOf(RemoteTouchScreen::class, $wait);
    }

    /**
     * @covers ::action
     */
    public function testShouldCreateWebDriverActionsInstance(): void
    {
        $wait = $this->driver->action();

        $this->assertInstanceOf(WebDriverActions::class, $wait);
    }

    /**
     * @covers ::wait
     */
    public function testShouldCreateWebDriverWaitInstance(): void
    {
        $wait = $this->driver->wait(15, 1337);

        $this->assertInstanceOf(WebDriverWait::class, $wait);
    }

    /**
     * @covers ::findElements
     * @covers \Facebook\WebDriver\Exception\Internal\UnexpectedResponseException
     */
    public function testShouldThrowExceptionOnUnexpectedValueFromRemoteEndWhenFindingElements(): void
    {
        $executorMock = $this->createMock(HttpCommandExecutor::class);
        $executorMock->expects($this->once())
            ->method('execute')
            ->with($this->isInstanceOf(WebDriverCommand::class))
            ->willReturn(new WebDriverResponse('session-id'));

        $this->driver->setCommandExecutor($executorMock);

        $this->expectException(UnexpectedResponseException::class);
        $this->expectExceptionMessage('Server response to findElements command is not an array');
        $this->driver->findElements($this->createMock(WebDriverBy::class));
    }
}
