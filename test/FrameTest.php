<?php
require_once(dirname(__FILE__) . '/../PHPWebDriver/WebDriver.php');
require_once(dirname(__FILE__) . '/../PHPWebDriver/WebDriverWait.php');

class FrameTest extends PHPUnit_Framework_TestCase {
  protected static $session;
  protected static $fp;
  
  public static function setUpBeforeClass() {
    $driver = new PHPWebDriver_WebDriver();
    self::$session = $driver->session(); // firefox
    self::$session->open("http://ckeditor.com/demo");
  }
  
  public static function tearDownAfterClass() {
    self::$session->close();
  }
  
  public function test_frame_stuff() {
    $divs = self::$session->elements("css selector", "div");
    // find your iframe
    $iframe = self::$session->element("css selector", "iframe");
    // switch context to it
    self::$session->switch_to_frame($iframe);
    // interact
    $ps = self::$session->elements("css selector", "p");
    $this->assertEquals(count($ps), 6);
    // switch back
    self::$session->switch_to_frame();
  }
}
