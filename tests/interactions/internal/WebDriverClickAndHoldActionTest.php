<?php

class WebDriverClickAndHoldActionTest extends PHPUnit_Framework_TestCase
{
  /**
   * @type WebDriverClickAndHoldAction
   */
  private $webDriverClickAndHoldAction;

  private $webDriverMouse;
  private $locationProvider;

  public function setUp() {
    $this->webDriverMouse = $this->getMock('WebDriverMouse');
    $this->locationProvider = $this->getMock('WebDriverLocatable');
    $this->webDriverClickAndHoldAction = new WebDriverClickAndHoldAction(
      $this->webDriverMouse,
      $this->locationProvider
    );
  }

  public function testPerformSendsMouseDownCommand() {
    $coords = $this->getMockBuilder('WebDriverCoordinates')->disableOriginalConstructor()->getMock();
    $this->webDriverMouse->expects($this->once())->method('mouseDown')->with($coords);
    $this->locationProvider->expects($this->once())->method('getCoordinates')->will($this->returnValue($coords));
    $this->webDriverClickAndHoldAction->perform();
  }
}
