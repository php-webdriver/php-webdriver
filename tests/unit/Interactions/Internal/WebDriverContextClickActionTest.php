<?php

namespace Facebook\WebDriver\Interactions\Internal;

use Facebook\WebDriver\Internal\WebDriverLocatable;
use Facebook\WebDriver\WebDriverMouse;
use PHPUnit\Framework\TestCase;

class WebDriverContextClickActionTest extends TestCase
{
    /** @var WebDriverContextClickAction */
    private $webDriverContextClickAction;
    /** @var WebDriverMouse|\PHPUnit_Framework_MockObject_MockObject */
    private $webDriverMouse;
    /** @var WebDriverLocatable|\PHPUnit_Framework_MockObject_MockObject */
    private $locationProvider;

    protected function setUp()
    {
        $this->webDriverMouse = $this->getMockBuilder(WebDriverMouse::class)->getMock();
        $this->locationProvider = $this->getMockBuilder(WebDriverLocatable::class)->getMock();
        $this->webDriverContextClickAction = new WebDriverContextClickAction(
            $this->webDriverMouse,
            $this->locationProvider
        );
    }

    public function testPerformSendsContextClickCommand()
    {
        $coords = $this->getMockBuilder(WebDriverCoordinates::class)
            ->disableOriginalConstructor()->getMock();
        $this->webDriverMouse->expects($this->once())->method('contextClick')->with($coords);
        $this->locationProvider->expects($this->once())->method('getCoordinates')->willReturn($coords);
        $this->webDriverContextClickAction->perform();
    }
}
