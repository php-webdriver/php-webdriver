<?php

namespace Facebook\WebDriver\Interactions\Internal;

use Facebook\WebDriver\Internal\WebDriverLocatable;
use Facebook\WebDriver\WebDriverKeyboard;
use Facebook\WebDriver\WebDriverMouse;
use PHPUnit\Framework\TestCase;

class WebDriverKeyUpActionTest extends TestCase
{
    /** @var WebDriverKeyUpAction */
    private $webDriverKeyUpAction;
    /** @var WebDriverKeyboard|\PHPUnit_Framework_MockObject_MockObject */
    private $webDriverKeyboard;
    /** @var WebDriverMouse|\PHPUnit_Framework_MockObject_MockObject */
    private $webDriverMouse;
    /** @var WebDriverLocatable|\PHPUnit_Framework_MockObject_MockObject */
    private $locationProvider;

    protected function setUp()
    {
        $this->webDriverKeyboard = $this->getMockBuilder(WebDriverKeyboard::class)->getMock();
        $this->webDriverMouse = $this->getMockBuilder(WebDriverMouse::class)->getMock();
        $this->locationProvider = $this->getMockBuilder(WebDriverLocatable::class)->getMock();

        $this->webDriverKeyUpAction = new WebDriverKeyUpAction(
            $this->webDriverKeyboard,
            $this->webDriverMouse,
            $this->locationProvider,
            'a'
        );
    }

    public function testPerformFocusesOnElementAndSendPressKeyCommand()
    {
        $coords = $this->getMockBuilder(WebDriverCoordinates::class)
            ->disableOriginalConstructor()->getMock();
        $this->webDriverMouse->expects($this->once())->method('click')->with($coords);
        $this->locationProvider->expects($this->once())->method('getCoordinates')->willReturn($coords);
        $this->webDriverKeyboard->expects($this->once())->method('releaseKey')->with('a');
        $this->webDriverKeyUpAction->perform();
    }
}
