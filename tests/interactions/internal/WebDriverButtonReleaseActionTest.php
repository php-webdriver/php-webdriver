<?php

class WebDriverButtonReleaseActionTest extends PHPUnit_Framework_TestCase
{
  /**
   * @type WebDriverButtonReleaseAction
   */
  private $webDriverButtonReleaseAction;

  private $webDriverMouse;
  private $locationProvider;

  public function setUp() {
    $this->webDriverMouse = $this->getMock('WebDriverMouse');
    $this->locationProvider = $this->getMock('WebDriverLocatable');
    $this->webDriverButtonReleaseAction = new WebDriverButtonReleaseAction(
      $this->webDriverMouse,
      $this->locationProvider
    );
  }

  public function testPerformSendsMouseUpCommand() {
    $coords = $this->getMockBuilder('WebDriverCoordinates')->disableOriginalConstructor()->getMock();
    $this->webDriverMouse->expects($this->once())->method('mouseUp')->with($coords);
    $this->locationProvider->expects($this->once())->method('getCoordinates')->will($this->returnValue($coords));
    $this->webDriverButtonReleaseAction->perform();
  }
}
