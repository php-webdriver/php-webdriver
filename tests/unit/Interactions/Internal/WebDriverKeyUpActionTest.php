<?php declare(strict_types=1);

namespace Facebook\WebDriver\Interactions\Internal;

use Facebook\WebDriver\Internal\WebDriverLocatable;
use Facebook\WebDriver\WebDriverKeyboard;
use Facebook\WebDriver\WebDriverKeys;
use Facebook\WebDriver\WebDriverMouse;
use PHPUnit\Framework\TestCase;

class WebDriverKeyUpActionTest extends TestCase
{
    /** @var WebDriverKeyUpAction */
    private $webDriverKeyUpAction;
    /** @var WebDriverKeyboard|\PHPUnit\Framework\MockObject\MockObject */
    private $webDriverKeyboard;
    /** @var WebDriverMouse|\PHPUnit\Framework\MockObject\MockObject */
    private $webDriverMouse;
    /** @var WebDriverLocatable|\PHPUnit\Framework\MockObject\MockObject */
    private $locationProvider;

    protected function setUp(): void
    {
        $this->webDriverKeyboard = $this->getMockBuilder(WebDriverKeyboard::class)->getMock();
        $this->webDriverMouse = $this->getMockBuilder(WebDriverMouse::class)->getMock();
        $this->locationProvider = $this->getMockBuilder(WebDriverLocatable::class)->getMock();

        $this->webDriverKeyUpAction = new WebDriverKeyUpAction(
            $this->webDriverKeyboard,
            $this->webDriverMouse,
            $this->locationProvider,
            WebDriverKeys::LEFT_SHIFT
        );
    }

    public function testPerformFocusesOnElementAndSendPressKeyCommand(): void
    {
        $coords = $this->createMock(WebDriverCoordinates::class);
        $this->webDriverMouse->expects($this->once())->method('click')->with($coords);
        $this->locationProvider->expects($this->once())->method('getCoordinates')->willReturn($coords);
        $this->webDriverKeyboard->expects($this->once())->method('releaseKey')->with(WebDriverKeys::LEFT_SHIFT);
        $this->webDriverKeyUpAction->perform();
    }
}
