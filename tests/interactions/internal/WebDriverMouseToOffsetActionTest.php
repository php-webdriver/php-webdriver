<?php

class WebDriverMouseToOffsetActionTest extends PHPUnit_Framework_TestCase
{
  /**
   * @type WebDriverMoveToOffsetAction
   */
  private $webDriverMoveToOffsetAction;

  private $webDriverMouse;
  private $locationProvider;

  public function setUp() {
    $this->webDriverMouse = $this->getMock('WebDriverMouse');
    $this->locationProvider = $this->getMock('WebDriverLocatable');
    $this->webDriverMoveToOffsetAction = new WebDriverMoveToOffsetAction(
      $this->webDriverMouse,
      $this->locationProvider,
      150,
      200
    );
  }

  public function testPerformFocusesOnElementAndSendPressKeyCommand() {
    $coords = $this->getMockBuilder('WebDriverCoordinates')->disableOriginalConstructor()->getMock();
    $this->webDriverMouse->expects($this->once())->method('mouseMove')->with($coords, 150, 200);
    $this->locationProvider->expects($this->once())->method('getCoordinates')->will($this->returnValue($coords));
    $this->webDriverMoveToOffsetAction->perform();
  }
}
