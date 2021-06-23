<?php

namespace PhpWebDriver\WebDriver\Interactions\Internal;

use PhpWebDriver\WebDriver\Internal\WebDriverLocatable;
use PhpWebDriver\WebDriver\WebDriverMouse;
use PHPUnit\Framework\TestCase;

class WebDriverMouseMoveActionTest extends TestCase
{
    /** @var WebDriverMouseMoveAction */
    private $webDriverMouseMoveAction;
    /** @var WebDriverMouse|\PHPUnit\Framework\MockObject\MockObject */
    private $webDriverMouse;
    /** @var WebDriverLocatable|\PHPUnit\Framework\MockObject\MockObject */
    private $locationProvider;

    protected function setUp(): void
    {
        $this->webDriverMouse = $this->getMockBuilder(WebDriverMouse::class)->getMock();
        $this->locationProvider = $this->getMockBuilder(WebDriverLocatable::class)->getMock();

        $this->webDriverMouseMoveAction = new WebDriverMouseMoveAction(
            $this->webDriverMouse,
            $this->locationProvider
        );
    }

    public function testPerformFocusesOnElementAndSendPressKeyCommand()
    {
        $coords = $this->getMockBuilder(WebDriverCoordinates::class)
            ->disableOriginalConstructor()->getMock();
        $this->webDriverMouse->expects($this->once())->method('mouseMove')->with($coords);
        $this->locationProvider->expects($this->once())->method('getCoordinates')->willReturn($coords);
        $this->webDriverMouseMoveAction->perform();
    }
}
