<?php

class WebDriverKeyUpActionTest extends PHPUnit_Framework_TestCase
{
  /**
   * @type WebDriverKeyUpAction
   */
  private $webDriverKeyUpAction;

  private $webDriverKeyboard;
  private $webDriverMouse;
  private $locationProvider;

  public function setUp() {
    $this->webDriverKeyboard = $this->getMock('WebDriverKeyboard');
    $this->webDriverMouse = $this->getMock('WebDriverMouse');
    $this->locationProvider = $this->getMock('WebDriverLocatable');
    $this->webDriverKeyUpAction = new WebDriverKeyUpAction(
      $this->webDriverKeyboard,
      $this->webDriverMouse,
      $this->locationProvider,
      'a'
    );
  }

  public function testPerformFocusesOnElementAndSendPressKeyCommand() {
    $coords = $this->getMockBuilder('WebDriverCoordinates')->disableOriginalConstructor()->getMock();
    $this->webDriverMouse->expects($this->once())->method('click')->with($coords);
    $this->locationProvider->expects($this->once())->method('getCoordinates')->will($this->returnValue($coords));
    $this->webDriverKeyboard->expects($this->once())->method('releaseKey')->with('a');
    $this->webDriverKeyUpAction->perform();
  }
}
