<?php

namespace PhpWebDriver\WebDriver\Interactions\Internal;

use PhpWebDriver\WebDriver\Internal\WebDriverLocatable;
use PhpWebDriver\WebDriver\WebDriverMouse;
use PHPUnit\Framework\TestCase;

class WebDriverClickActionTest extends TestCase
{
    /** @var WebDriverClickAction */
    private $webDriverClickAction;
    /** @var WebDriverMouse|\PHPUnit\Framework\MockObject\MockObject */
    private $webDriverMouse;
    /** @var WebDriverLocatable|\PHPUnit\Framework\MockObject\MockObject */
    private $locationProvider;

    protected function setUp(): void
    {
        $this->webDriverMouse = $this->getMockBuilder(WebDriverMouse::class)->getMock();
        $this->locationProvider = $this->getMockBuilder(WebDriverLocatable::class)->getMock();
        $this->webDriverClickAction = new WebDriverClickAction(
            $this->webDriverMouse,
            $this->locationProvider
        );
    }

    public function testPerformSendsClickCommand()
    {
        $coords = $this->getMockBuilder(WebDriverCoordinates::class)
            ->disableOriginalConstructor()->getMock();
        $this->webDriverMouse->expects($this->once())->method('click')->with($coords);
        $this->locationProvider->expects($this->once())->method('getCoordinates')->willReturn($coords);
        $this->webDriverClickAction->perform();
    }
}
