<?php

class WebDriverKeyDownActionTest extends PHPUnit_Framework_TestCase
{
  /**
   * @type WebDriverKeyDownAction
   */
  private $webDriverKeyDownAction;

  private $webDriverKeyboard;
  private $webDriverMouse;
  private $locationProvider;

  public function setUp() {
    $this->webDriverKeyboard = $this->getMock('WebDriverKeyboard');
    $this->webDriverMouse = $this->getMock('WebDriverMouse');
    $this->locationProvider = $this->getMock('WebDriverLocatable');
    $this->webDriverKeyDownAction = new WebDriverKeyDownAction(
      $this->webDriverKeyboard,
      $this->webDriverMouse,
      $this->locationProvider
    );
  }

  public function testPerformFocusesOnElementAndSendPressKeyCommand() {
    $coords = $this->getMockBuilder('WebDriverCoordinates')->disableOriginalConstructor()->getMock();
    $this->webDriverMouse->expects($this->once())->method('click')->with($coords);
    $this->locationProvider->expects($this->once())->method('getCoordinates')->will($this->returnValue($coords));
    $this->webDriverKeyboard->expects($this->once())->method('pressKey');
    $this->webDriverKeyDownAction->perform();
  }
}
