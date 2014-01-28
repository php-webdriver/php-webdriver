<?php

class WebDriverDoubleClickActionTest extends PHPUnit_Framework_TestCase
{
  /**
   * @type WebDriverDoubleClickAction
   */
  private $webDriverDoubleClickAction;

  private $webDriverMouse;
  private $locationProvider;

  public function setUp() {
    $this->webDriverMouse = $this->getMock('WebDriverMouse');
    $this->locationProvider = $this->getMock('WebDriverLocatable');
    $this->webDriverDoubleClickAction = new WebDriverDoubleClickAction(
      $this->webDriverMouse,
      $this->locationProvider
    );
  }

  public function testPerformSendsDoubleClickCommand() {
    $coords = $this->getMockBuilder('WebDriverCoordinates')->disableOriginalConstructor()->getMock();
    $this->webDriverMouse->expects($this->once())->method('doubleClick')->with($coords);
    $this->locationProvider->expects($this->once())->method('getCoordinates')->will($this->returnValue($coords));
    $this->webDriverDoubleClickAction->perform();
  }
}
