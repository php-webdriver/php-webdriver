<?php

require_once __DIR__ . '/../../../lib/__init__.php';

class WebDriverClickActionTest extends PHPUnit_Framework_TestCase
{
    /**
     * @type WebDriverButtonReleaseAction
     */
    private $webDriverClickAction;

    private $webDriverMouse;
    private $locationProvider;

    public function setUp()
    {
        $this->webDriverMouse = $this->getMock('WebDriverMouse');
        $this->locationProvider = $this->getMock('WebDriverLocatable');
        $this->webDriverClickAction = new WebDriverClickAction(
            $this->webDriverMouse,
            $this->locationProvider
        );
    }

    public function testPerformSendsClickCommand()
    {
        $coords = $this->getMockBuilder('WebDriverCoordinates')->disableOriginalConstructor()->getMock();
        $this->webDriverMouse->expects($this->once())->method('click');
        $this->locationProvider->expects($this->once())->method('getCoordinates')->will($this->returnValue($coords));
        $this->webDriverClickAction->perform();
    }
}
