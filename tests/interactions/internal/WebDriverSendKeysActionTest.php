<?php

class WebDriverSendKeysActionTest extends PHPUnit_Framework_TestCase
{
  /**
   * @type WebDriverSendKeysAction
   */
  private $webDriverSendKeysAction;

  private $webDriverKeyboard;
  private $webDriverMouse;
  private $locationProvider;
  private $keys;

  public function setUp() {
    $this->webDriverKeyboard = $this->getMock('WebDriverKeyboard');
    $this->webDriverMouse = $this->getMock('WebDriverMouse');
    $this->locationProvider = $this->getMock('WebDriverLocatable');
    $this->keys = array('t', 'e', 's', 't');
    $this->webDriverSendKeysAction = new WebDriverSendKeysAction(
      $this->webDriverKeyboard,
      $this->webDriverMouse,
      $this->locationProvider,
      $this->keys
    );
  }

  public function testPerformFocusesOnElementAndSendPressKeyCommand() {
    $coords = $this->getMockBuilder('WebDriverCoordinates')->disableOriginalConstructor()->getMock();
    $this->webDriverKeyboard->expects($this->once())->method('sendKeys')->with($this->keys);
    $this->webDriverMouse->expects($this->once())->method('click')->with($coords);
    $this->locationProvider->expects($this->once())->method('getCoordinates')->will($this->returnValue($coords));
    $this->webDriverSendKeysAction->perform();
  }
}
