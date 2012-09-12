<?php
require_once(dirname(__FILE__) . '/../PHPWebDriver/WebDriver.php');

class ElementsTest extends PHPUnit_Framework_TestCase {
  protected static $driver;
  
  public function setUp() {
      self::$driver = new PHPWebDriver_WebDriver();
  }
  
  public function tearDown() {
      $this->session->close();
  }
  
  /**
  * @group elements
  */
  public function testElementsExist() {
      $this->session = self::$driver->session(); // firefox
      $this->session->open("https://github.com/element-34/php-webdriver");
      $e = $this->session->elements('css selector', '.tabs li');
      $this->assertEquals(count($e), 7);
  }

  /**
  * @group elements
  */
  public function testElementsDoesNotExist() {
      $this->session = self::$driver->session(); // firefox
      $this->session->open("https://github.com/element-34/php-webdriver");
      $e = $this->session->elements('css selector', '.flyingmonkey');
      $this->assertEquals(count($e), 0);
  }
  
  /**
  * @group elements
  */
  public function testElementsChaining() {
      $this->session = self::$driver->session(); // firefox
      $this->session->open("https://github.com/element-34/php-webdriver");
      $tabs = $this->session->elements('css selector', '.tabs');
      $li = $tabs[0]->elements('css selector', 'li');
      $this->assertEquals(count($li), 6);
  }
}
?>