<?php
require_once(dirname(__FILE__) . '/../PHPWebDriver/Support/HTML5/Video.php');
require_once(dirname(__FILE__) . '/../PHPWebDriver/WebDriver.php');
 
class VideoTest extends PHPUnit_Framework_TestCase {
  protected static $session;
  
  public static function setUpBeforeClass() {
    $driver = new PHPWebDriver_WebDriver();
    self::$session = $driver->session(); // firefox
    self::$session->open("http://html5demos.com/video");
  }
  
  public static function tearDownAfterClass() {
      self::$session->close();
  }
  
  public function testGetHeight() {
    $e = self::$session->element("tag name", 'video');
    $v = new PHPWebDriver_WebDriver_Support_HTML5_Video($e);
    $this->assertEquals(0, $v->height);
  }

  public function testSetHeight() {
    $e = self::$session->element("tag name", 'video');
    $v = new PHPWebDriver_WebDriver_Support_HTML5_Video($e);
    $v->height = 600;
    $e = self::$session->element("tag name", 'video');
    $v = new PHPWebDriver_WebDriver_Support_HTML5_Video($e);
    $this->assertEquals(600, $v->height);
  }

  public function testGetWidth() {
    $e = self::$session->element("tag name", 'video');
    $v = new PHPWebDriver_WebDriver_Support_HTML5_Video($e);
    $this->assertEquals(0, $v->width);
  }

  public function testSetWidth() {
    $e = self::$session->element("tag name", 'video');
    $v = new PHPWebDriver_WebDriver_Support_HTML5_Video($e);
    $v->width = 600;
    $e = self::$session->element("tag name", 'video');
    $v = new PHPWebDriver_WebDriver_Support_HTML5_Video($e);
    $this->assertEquals(600, $v->width);
  }

  public function testGetVideoHeight() {
    $e = self::$session->element("tag name", 'video');
    $v = new PHPWebDriver_WebDriver_Support_HTML5_Video($e);
    $this->assertEquals(360, $v->videoHeight);
  }

  public function testGetVideoWidth() {
    $e = self::$session->element("tag name", 'video');
    $v = new PHPWebDriver_WebDriver_Support_HTML5_Video($e);
    $this->assertEquals(480, $v->videoWidth);
  }

  public function testGetPoster() {
    $e = self::$session->element("tag name", 'video');
    $v = new PHPWebDriver_WebDriver_Support_HTML5_Video($e);
    $this->assertEquals('', $v->poster);
  }
  
  public function testSetPoster() {
    $e = self::$session->element("tag name", 'video');
    $v = new PHPWebDriver_WebDriver_Support_HTML5_Video($e);
    $v->poster = "http://foo.com/bar.png";
    $e = self::$session->element("tag name", 'video');
    $v = new PHPWebDriver_WebDriver_Support_HTML5_Video($e);
    $this->assertEquals("http://foo.com/bar.png", $v->poster);
  }
  
  /**
   * @expectedException PHPWebDriver_ObsoleteElementWebDriverError
   */
  public function testStaleAttribute() {
    $e = self::$session->element("tag name", 'video');
    $v = new PHPWebDriver_WebDriver_Support_HTML5_Video($e);
    $v->height = 600;
    $this->assertEquals(600, $v->height);
  }
  
  public function testGetParentProperty() {
    $e = self::$session->element("tag name", 'video');
    $v = new PHPWebDriver_WebDriver_Support_HTML5_Video($e);
    $this->assertEquals("metadata", $v->preload);
  }
  
  /**
   * @expectedException UnexpectedValueException
   */
  public function testWrongType() {
    $e = self::$session->element("tag name", 'video');
    $v = new PHPWebDriver_WebDriver_Support_HTML5_Video($e);
    $v->height = "600";
  }
  
}
?>
