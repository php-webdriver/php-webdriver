<?php
require_once(dirname(__FILE__) . '/../PHPWebDriver/WebDriver.php');
require_once(dirname(__FILE__) . '/../PHPWebDriver/WebDriverFirefoxProfile.php');
 
class ProfileTest extends PHPUnit_Framework_TestCase {
  /**
  * @group profile
  */
  public function testProfileExists() {
    $driver = new PHPWebDriver_WebDriver();
    $profile = new PHPWebDriver_WebDriverFirefoxProfile(dirname(__FILE__) . '/support/profiles/red');
    // var_dump($profile);
    $session = $driver->session('firefox', array(), array(), $browser_profile=$profile);
    $session->close();
  }
}