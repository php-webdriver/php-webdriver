<?php
require_once(dirname(__FILE__) . '/../PHPWebDriver/WebDriver.php');
require_once(dirname(__FILE__) . '/../PHPWebDriver/WebDriverActionChains.php');

class ExtraArgsTest extends PHPUnit_Framework_TestCase {
  protected static $driver;
  
  public function setUp() {
      self::$driver = new PHPWebDriver_WebDriver();
  }
  
  public function tearDown() {
      $this->session->close();
  }
  
  public function testNoArgs() {
      $this->session = self::$driver->session(); // firefox
  }

  public function testWithArgs() {
      $this->session = self::$driver->session('firefox', array(), array(CURLOPT_VERBOSE => true)); // firefox
  }
  
  /**
  * @group curl_opts
  * @group actionchains
  */
  public function testActionChangeWithOutArgs() {
      $this->session = self::$driver->session(); // firefox
      $this->session->open("https://github.com/element-34/php-webdriver");
      $ac = new \PHPWebDriver_WebDriverActionChains($this->session);
      $ac->moveToElement($this->session->element("css selector", ".watch-button a.minibutton"));
      $ac->perform();
      sleep(4);
  }

  /**
  * @group curl_opts
  * @group actionchains
  */
  public function testActionChangeWithArgs() {
      $this->session = self::$driver->session(); // firefox
      $this->session->open("https://github.com/element-34/php-webdriver");
      $ac = new \PHPWebDriver_WebDriverActionChains($this->session);
      $ac->moveToElement($this->session->element("css selector", ".watch-button a.minibutton"), array(CURLOPT_VERBOSE => true));
      $ac->perform();
      sleep(4);
  }

  /**
  * @group curl_opts
  * @group element
  */
  public function testElementWithArgs() {
      $this->session = self::$driver->session(); // firefox
      $this->session->open("https://github.com/element-34/php-webdriver");
      $e = $this->session->element("css selector", ".watch-button a.minibutton", array(CURLOPT_VERBOSE => true));
  }

  /**
  * @group curl_opts
  * @group elements
  * @group new
  */
  public function testElementsWithArgs() {
      $this->session = self::$driver->session(); // firefox
      $this->session->open("https://github.com/element-34/php-webdriver");
      $e = $this->session->elements("css selector", ".watch-button", array(CURLOPT_VERBOSE => true));
  }


}
?>