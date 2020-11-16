<?php

namespace Facebook\WebDriver\Interactions\Internal;

use Facebook\WebDriver\Internal\WebDriverLocatable;
use Facebook\WebDriver\WebDriverKeyboard;
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
        $this->webDriverKeyboard = $this->getMockBuilder(WebDriverKeyboard::class)->getMock();
        $this->webDriverMouse = $this->getMockBuilder(WebDriverMouse::class)->getMock();
        $this->locationProvider = $this->getMockBuilder(WebDriverLocatable::class)->getMock();

        $this->webDriverKeyDownAction = new WebDriverKeyDownAction(
            $this->webDriverKeyboard,
            $this->webDriverMouse,
            $this->locationProvider
        );
    }

    public function testPerformFocusesOnElementAndSendPressKeyCommand()
    {
        $coords = $this->getMockBuilder(WebDriverCoordinates::class)
            ->disableOriginalConstructor()->getMock();
        $this->webDriverMouse->expects($this->once())->method('click')->with($coords);
        $this->locationProvider->expects($this->once())->method('getCoordinates')->willReturn($coords);
        $this->webDriverKeyboard->expects($this->once())->method('pressKey');
        $this->webDriverKeyDownAction->perform();
    }
}
