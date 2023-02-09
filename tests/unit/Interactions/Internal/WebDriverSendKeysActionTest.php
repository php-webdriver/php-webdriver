<?php declare(strict_types=1);

namespace Facebook\WebDriver\Interactions\Internal;

use Facebook\WebDriver\Internal\WebDriverLocatable;
use Facebook\WebDriver\WebDriverKeyboard;
use Facebook\WebDriver\WebDriverMouse;
use PHPUnit\Framework\TestCase;

class WebDriverSendKeysActionTest extends TestCase
{
    /** @var WebDriverSendKeysAction */
    private $webDriverSendKeysAction;
    /** @var WebDriverKeyboard|\PHPUnit\Framework\MockObject\MockObject */
    private $webDriverKeyboard;
    /** @var WebDriverMouse|\PHPUnit\Framework\MockObject\MockObject */
    private $webDriverMouse;
    /** @var WebDriverLocatable|\PHPUnit\Framework\MockObject\MockObject */
    private $locationProvider;
    /** @var array */
    private $keys;

    protected function setUp(): void
    {
        $this->webDriverKeyboard = $this->getMockBuilder(WebDriverKeyboard::class)->getMock();
        $this->webDriverMouse = $this->getMockBuilder(WebDriverMouse::class)->getMock();
        $this->locationProvider = $this->getMockBuilder(WebDriverLocatable::class)->getMock();

        $this->keys = ['t', 'e', 's', 't'];
        $this->webDriverSendKeysAction = new WebDriverSendKeysAction(
            $this->webDriverKeyboard,
            $this->webDriverMouse,
            $this->locationProvider,
            $this->keys
        );
    }

    public function testPerformFocusesOnElementAndSendPressKeyCommand(): void
    {
        $coords = $this->getMockBuilder(WebDriverCoordinates::class)
            ->disableOriginalConstructor()->getMock();
        $this->webDriverKeyboard->expects($this->once())->method('sendKeys')->with($this->keys);
        $this->webDriverMouse->expects($this->once())->method('click')->with($coords);
        $this->locationProvider->expects($this->once())->method('getCoordinates')->willReturn($coords);
        $this->webDriverSendKeysAction->perform();
    }
}
