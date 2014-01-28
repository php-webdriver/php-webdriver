<?php

class WebDriverContextClickActionTest extends PHPUnit_Framework_TestCase
{
  /**
   * @type WebDriverContextClickAction
   */
  private $webDriverContextClickAction;

  private $webDriverMouse;
  private $locationProvider;

  public function setUp() {
    $this->webDriverMouse = $this->getMock('WebDriverMouse');
    $this->locationProvider = $this->getMock('WebDriverLocatable');
    $this->webDriverContextClickAction = new WebDriverContextClickAction(
      $this->webDriverMouse,
      $this->locationProvider
    );
  }

  public function testPerformSendsContextClickCommand() {
    $coords = $this->getMockBuilder('WebDriverCoordinates')->disableOriginalConstructor()->getMock();
    $this->webDriverMouse->expects($this->once())->method('contextClick');
    $this->locationProvider->expects($this->once())->method('getCoordinates')->will($this->returnValue($coords));
    $this->webDriverContextClickAction->perform();
  }
}
