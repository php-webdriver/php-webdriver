<?php declare(strict_types=1);

namespace Facebook\WebDriver\Interactions\Internal;

use Facebook\WebDriver\Internal\WebDriverLocatable;
use Facebook\WebDriver\WebDriverMouse;
use PHPUnit\Framework\TestCase;

class WebDriverMouseToOffsetActionTest extends TestCase
{
    /**
     * @type WebDriverMoveToOffsetAction
     */
    private $webDriverMoveToOffsetAction;
    /** @var WebDriverMouse|\PHPUnit\Framework\MockObject\MockObject */
    private $webDriverMouse;
    /** @var WebDriverLocatable|\PHPUnit\Framework\MockObject\MockObject */
    private $locationProvider;

    protected function setUp(): void
    {
        $this->webDriverMouse = $this->getMockBuilder(WebDriverMouse::class)->getMock();
        $this->locationProvider = $this->getMockBuilder(WebDriverLocatable::class)->getMock();

        $this->webDriverMoveToOffsetAction = new WebDriverMoveToOffsetAction(
            $this->webDriverMouse,
            $this->locationProvider,
            150,
            200
        );
    }

    public function testPerformFocusesOnElementAndSendPressKeyCommand(): void
    {
        $coords = $this->getMockBuilder(WebDriverCoordinates::class)
            ->disableOriginalConstructor()->getMock();
        $this->webDriverMouse->expects($this->once())->method('mouseMove')->with($coords, 150, 200);
        $this->locationProvider->expects($this->once())->method('getCoordinates')->willReturn($coords);
        $this->webDriverMoveToOffsetAction->perform();
    }
}
