<?php

namespace PhpWebDriver\WebDriver\Interactions\Internal;

use PhpWebDriver\WebDriver\Internal\WebDriverLocatable;
use PhpWebDriver\WebDriver\WebDriverMouse;
use PHPUnit\Framework\TestCase;

class WebDriverButtonReleaseActionTest extends TestCase
{
    /** @var WebDriverButtonReleaseAction */
    private $webDriverButtonReleaseAction;
    /** @var WebDriverMouse|\PHPUnit\Framework\MockObject\MockObject */
    private $webDriverMouse;
    /** @var WebDriverLocatable|\PHPUnit\Framework\MockObject\MockObject */
    private $locationProvider;

    protected function setUp(): void
    {
        $this->webDriverMouse = $this->getMockBuilder(WebDriverMouse::class)->getMock();
        $this->locationProvider = $this->getMockBuilder(WebDriverLocatable::class)->getMock();
        $this->webDriverButtonReleaseAction = new WebDriverButtonReleaseAction(
            $this->webDriverMouse,
            $this->locationProvider
        );
    }

    public function testPerformSendsMouseUpCommand()
    {
        $coords = $this->getMockBuilder(WebDriverCoordinates::class)
            ->disableOriginalConstructor()->getMock();
        $this->webDriverMouse->expects($this->once())->method('mouseUp')->with($coords);
        $this->locationProvider->expects($this->once())->method('getCoordinates')->willReturn($coords);
        $this->webDriverButtonReleaseAction->perform();
    }
}
