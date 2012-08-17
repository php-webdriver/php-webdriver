<?php
require_once(dirname(__FILE__) . '/../PHPWebDriver/WebDriver.php');

class DeleteWindowTest extends PHPUnit_Framework_TestCase {
  protected static $driver;
  
  public function setUp() {
      self::$driver = new PHPWebDriver_WebDriver();
  }
  
  /**
  * @group delete_window
  * @expectedException PHPWebDriver_UnhandledWebDriverError
  */
  public function testDeleteWindow() {
      $this->session = self::$driver->session(); // firefox
      $this->session->open("https://github.com/element-34/php-webdriver");
      $this->session->deleteWindow();
      sleep(3);
      $this->assertEquals(count($this->session->window_handles()), 0);
  }

}
?>