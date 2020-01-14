<?php

namespace Facebook\WebDriver\Interactions\Internal;

use Facebook\WebDriver\Internal\WebDriverLocatable;
use Facebook\WebDriver\WebDriverMouse;
use PHPUnit\Framework\TestCase;

class WebDriverDoubleClickActionTest extends TestCase
{
    /** @var WebDriverDoubleClickAction */
    private $webDriverDoubleClickAction;
    /** @var WebDriverMouse|\PHPUnit_Framework_MockObject_MockObject */
    private $webDriverMouse;
    /** @var WebDriverLocatable|\PHPUnit_Framework_MockObject_MockObject */
    private $locationProvider;

    protected function setUp()
    {
        $this->webDriverMouse = $this->getMockBuilder(WebDriverMouse::class)->getMock();
        $this->locationProvider = $this->getMockBuilder(WebDriverLocatable::class)->getMock();
        $this->webDriverDoubleClickAction = new WebDriverDoubleClickAction(
            $this->webDriverMouse,
            $this->locationProvider
        );
    }

    public function testPerformSendsDoubleClickCommand()
    {
        $coords = $this->getMockBuilder(WebDriverCoordinates::class)
            ->disableOriginalConstructor()->getMock();
        $this->webDriverMouse->expects($this->once())->method('doubleClick')->with($coords);
        $this->locationProvider->expects($this->once())->method('getCoordinates')->willReturn($coords);
        $this->webDriverDoubleClickAction->perform();
    }
}
