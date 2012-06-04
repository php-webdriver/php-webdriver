<?php
require_once(dirname(__FILE__) . '/../PHPWebDriver/WebDriver.php');
 
class TimeoutTest extends PHPUnit_Framework_TestCase {
  protected static $session;
  
  public static function setUpBeforeClass() {
    $driver = new PHPWebDriver_WebDriver();
    self::$session = $driver->session(); // firefox
  }
  
  public static function tearDownAfterClass() {
      self::$session->close();
  }
  
  public function testImplicitTimeout() {
    self::$session->setTimeouts(array('type' => 'implicit', 'ms' => 5));
  }

  public function testImplicitTimeoutWrapper() {
    self::$session->implicitlyWait(5);
  }

  public function testScriptTimeout() {
    self::$session->setTimeouts(array('type' => 'script', 'ms' => 6));
  }

  public function testScriptTimeoutWrapper() {
    self::$session->setScriptTimeout(6);
  }

  public function testPageLoadTimeout() {
    self::$session->setTimeouts(array('type' => 'page load', 'ms' => 7));
  }
  
  public function testPageLoadTimeoutWrapper() {
    self::$session->setPageLoadTimeout(7);
  }
  
  /**
   * @expectedException PHPWebDriver_UnhandledWebDriverError
   */
  public function testMakeBelieveTimeout() {
    self::$session->setTimeouts(array('type' => 'make believe', 'ms' => 8));
  }

  /**
   * @expectedException PHPWebDriver_UnhandledWebDriverError
   */
  public function testMakeNullType() {
    self::$session->setTimeouts(array('type' => null, 'ms' => 8));
  }

}
?>