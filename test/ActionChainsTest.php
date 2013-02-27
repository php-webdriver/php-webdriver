<?php
require_once(dirname(__FILE__) . '/../PHPWebDriver/WebDriver.php');
require_once(dirname(__FILE__) . '/../PHPWebDriver/WebDriverActionChains.php');
require_once(dirname(__FILE__) . '/../PHPWebDriver/WebDriverBy.php');

class ProxyTest extends PHPUnit_Framework_TestCase {
  protected static $session;
  
  public function setUp() {
    $driver = new PHPWebDriver_WebDriver();
    self::$session = $driver->session('chrome');
  }
  
  public function tearDown() {
    self::$session->close();
  }

  /**
  * @group chains
  */  
  public function testDragAndDrop() {
    self::$session->open("http://jqueryui.com/droppable");
    $iframe = self::$session->element(PHPWebDriver_WebDriverBy::CSS_SELECTOR, ".demo-frame");
    self::$session->switch_to_frame($iframe);

    $draggable = self::$session->element(PHPWebDriver_WebDriverBy::ID, "draggable");
    $droppable = self::$session->element(PHPWebDriver_WebDriverBy::ID, "droppable");

    $chain = new PHPWebDriver_WebDriverActionChains(self::$session);
    $chain->dragAndDrop($draggable, $droppable);
    $chain->perform();
    
    $dropped = self::$session->element(PHPWebDriver_WebDriverBy::ID, "droppable");
    $this->AssertEquals($dropped->text(), 'Dropped!');
  }

}
?>