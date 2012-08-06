<?php
require_once(dirname(__FILE__) . '/../PHPWebDriver/WebDriver.php');

class CapabilitiesTest extends PHPUnit_Framework_TestCase {
  /**
  * @test
  * @group caps
  */
  public function testDefault() {
      $driver = new PHPWebDriver_WebDriver();
      $session = $driver->session(); // firefox
      $session->close();      
  }

  /**
  * @test
  * @group caps
  * @group firefox
  */
  public function testFirefox() {
      $driver = new PHPWebDriver_WebDriver();
      $session = $driver->session("firefox"); // firefox
      $session->close();      
  }

  /**
  * @test
  * @group caps
  * @group chrome
  */
  public function testGoogleChrome() {
      $driver = new PHPWebDriver_WebDriver();
      $session = $driver->session("chrome"); // chrome
      $session->close();      
  }

  /**
  * @test
  * @group caps
  * @group ie
  */
  public function testInternetExplorer() {
      $driver = new PHPWebDriver_WebDriver();
      $session = $driver->session("ie"); // ie
      $session->close();      
  }

  /**
  * @test
  * @group caps
  * @group opera
  */
  public function testOpera() {
      $driver = new PHPWebDriver_WebDriver();
      $session = $driver->session("opera"); // opera
      $session->close();      
  }

  /**
  * @test
  * @group caps
  * @group htmlunit
  */
  public function testHTMLUnit() {
      $driver = new PHPWebDriver_WebDriver();
      $session = $driver->session("htmlunit"); // htmlunit
      $session->close();      
  }

  /**
  * @test
  * @group caps
  * @group htmlunit
  */
  public function testHTMLUnitJS() {
      $driver = new PHPWebDriver_WebDriver();
      $session = $driver->session("htmlunitjs"); // htmlunitjs
      $session->close();      
  }
}
  
?>
