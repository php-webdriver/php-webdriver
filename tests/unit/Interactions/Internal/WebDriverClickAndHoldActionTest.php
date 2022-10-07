<?php

namespace Facebook\WebDriver\Interactions\Internal;

use Facebook\WebDriver\Internal\WebDriverLocatable;
use Facebook\WebDriver\WebDriverMouse;
use PHPUnit\Framework\TestCase;

class WebDriverClickAndHoldActionTest extends TestCase
{
    /** @var WebDriverClickAndHoldAction */
    private $webDriverClickAndHoldAction;
    /** @var WebDriverMouse|\PHPUnit\Framework\MockObject\MockObject */
    private $webDriverMouse;
    /** @var WebDriverLocatable|\PHPUnit\Framework\MockObject\MockObject */
    private $locationProvider;

    protected function setUp(): void
    {
        $this->webDriverMouse = $this->getMockBuilder(WebDriverMouse::class)->getMock();
        $this->locationProvider = $this->getMockBuilder(WebDriverLocatable::class)->getMock();
        $this->webDriverClickAndHoldAction = new WebDriverClickAndHoldAction(
            $this->webDriverMouse,
            $this->locationProvider
        );
    }

    public function testPerformSendsMouseDownCommand()
    {
        $coords = $this->getMockBuilder(WebDriverCoordinates::class)
            ->disableOriginalConstructor()->getMock();
        $this->webDriverMouse->expects($this->once())->method('mouseDown')->with($coords);
        $this->locationProvider->expects($this->once())->method('getCoordinates')->willReturn($coords);
        $this->webDriverClickAndHoldAction->perform();
    }
}
