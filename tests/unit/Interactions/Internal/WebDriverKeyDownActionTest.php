<?php declare(strict_types=1);

namespace Facebook\WebDriver\Interactions\Internal;

use Facebook\WebDriver\Internal\WebDriverLocatable;
use Facebook\WebDriver\WebDriverKeyboard;
use Facebook\WebDriver\WebDriverKeys;
use Facebook\WebDriver\WebDriverMouse;
use PHPUnit\Framework\TestCase;

class WebDriverKeyDownActionTest extends TestCase
{
    /** @var WebDriverKeyDownAction */
    private $webDriverKeyDownAction;
    /** @var WebDriverKeyboard|\PHPUnit\Framework\MockObject\MockObject */
    private $webDriverKeyboard;
    /** @var WebDriverMouse|\PHPUnit\Framework\MockObject\MockObject */
    private $webDriverMouse;
    /** @var WebDriverLocatable|\PHPUnit\Framework\MockObject\MockObject */
    private $locationProvider;

    protected function setUp(): void
    {
        $this->webDriverKeyboard = $this->createMock(WebDriverKeyboard::class);
        $this->webDriverMouse = $this->createMock(WebDriverMouse::class);
        $this->locationProvider = $this->createMock(WebDriverLocatable::class);

        $this->webDriverKeyDownAction = new WebDriverKeyDownAction(
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
        $this->webDriverKeyboard->expects($this->once())->method('pressKey');
        $this->webDriverKeyDownAction->perform();
    }
}
