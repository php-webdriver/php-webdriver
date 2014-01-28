<?php

class WebDriverMouseMoveActionTest extends PHPUnit_Framework_TestCase
{
  /**
   * @type WebDriverMouseMoveAction
   */
  private $webDriverMouseMoveAction;

  private $webDriverMouse;
  private $locationProvider;

  public function setUp() {
    $this->webDriverMouse = $this->getMock('WebDriverMouse');
    $this->locationProvider = $this->getMock('WebDriverLocatable');
    $this->webDriverMouseMoveAction = new WebDriverMouseMoveAction(
      $this->webDriverMouse,
      $this->locationProvider
    );
  }

  public function testPerformFocusesOnElementAndSendPressKeyCommand() {
    $coords = $this->getMockBuilder('WebDriverCoordinates')->disableOriginalConstructor()->getMock();
    $this->webDriverMouse->expects($this->once())->method('mouseMove')->with($coords);
    $this->locationProvider->expects($this->once())->method('getCoordinates')->will($this->returnValue($coords));
    $this->webDriverMouseMoveAction->perform();
  }
}
