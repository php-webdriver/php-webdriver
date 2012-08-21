<?php
require_once(dirname(__FILE__) . '/../PHPWebDriver/WebDriver.php');
 
class LocationTest extends PHPUnit_Framework_TestCase {
  protected static $session;
  
  public static function setUpBeforeClass() {
    $driver = new PHPWebDriver_WebDriver();
    self::$session = $driver->session(); // firefox
  }
  
  public static function tearDownAfterClass() {
    self::$session->close();
  }
  
  /**
  * @group element_location
  */
  public function testLocation() {
    self::$session->open("http://duckduckgo.com/");
    $e = self::$session->element("css selector", '[href="/settings.html"]');
    $location = $e->location();
    $this->assertEquals($location["x"], 842);
    $this->assertEquals($location["y"], 918);
  }

  /**
  * @group geo_location
  * @group get
  */
  public function testGeoLocationGet() {
    self::$session->open("http://duckduckgo.com/");
    var_dump(self::$session->location());
  }

  /**
  * @group geo_location
  * @group post
  */
  public function testGeoLocationPost() {
    self::$session->open("http://duckduckgo.com/");
    var_dump(self::$session->location());
  }

}
?>