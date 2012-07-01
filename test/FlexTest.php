<?php
require_once(dirname(__FILE__) . '/../PHPWebDriver/WebDriver.php');
require_once(dirname(__FILE__) . '/../PHPWebDriver/WebDriverWait.php');
require_once(dirname(__FILE__) . '/../PHPWebDriver/Support/FlashFlex/FlexPilot.php');

class FlexTest extends PHPUnit_Framework_TestCase {
  protected static $session;
  
  public static function setUpBeforeClass() {
    $driver = new PHPWebDriver_WebDriver();
    self::$session = $driver->session(); // firefox
    self::$session->open("http://localhost:8000/flexstore/flexstore.html");
  }
  
  public static function tearDownAfterClass() {
      self::$session->close();
  }
  
  public function test_flex_ready() {
    $e = self::$session->element("name", "flexstore");
    $fp = new PHPWebDriver_WebDriver_Support_FlashFlex_FlexPilot(self::$session, $e);
    $fp->wait_for_flex_ready(5);  
  }
}

?>