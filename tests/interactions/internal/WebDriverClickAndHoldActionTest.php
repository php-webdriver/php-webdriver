<?php

class WebDriverClickAndHoldActionTest extends PHPUnit_Framework_TestCase
{
    /**
     * @type WebDriverClickAndHoldAction
     */
    private $webDriverClickAction;

    private $webDriverMouse;
    private $locationProvider;

    public function setUp()
    {
        $this->webDriverMouse = $this->getMock('WebDriverMouse');
        $this->locationProvider = $this->getMock('WebDriverLocatable');
        $this->webDriverClickAction = new WebDriverClickAndHoldAction(
            $this->webDriverMouse,
            $this->locationProvider
        );
    }

    public function testPerformSendsMouseDownCommand()
    {
        $coords = $this->getMockBuilder('WebDriverCoordinates')->disableOriginalConstructor()->getMock();
        $this->webDriverMouse->expects($this->once())->method('mouseDown');
        $this->locationProvider->expects($this->once())->method('getCoordinates')->will($this->returnValue($coords));
        $this->webDriverClickAction->perform();
    }
}
