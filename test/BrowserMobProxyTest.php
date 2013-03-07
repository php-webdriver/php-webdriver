<?php
require_once(dirname(__FILE__) . '/../PHPWebDriver/WebDriver.php');
require_once(dirname(__FILE__) . '/../PHPWebDriver/WebDriverProxy.php');
require_once '/Users/adam/work/PHPBrowserMobProxy/PHPBrowserMobProxy/Client.php';


class ProxyTest extends PHPUnit_Framework_TestCase {
  protected static $driver;
  protected static $client;
  
  public function setUp() {
      self::$driver = new PHPWebDriver_WebDriver();
      self::$client = new PHPBrowserMobProxy_Client("localhost:8080");
  }
  
  public function tearDown() {
      $this->session->close();
      self::$client->close();
  }

  /**
  * @group proxy
  * @group chrome
  */  
  public function testChrome() {
      $additional_capabilities = array();
      $proxy = new PHPWebDriver_WebDriverProxy();
      $proxy->httpProxy = self::$client->url;
      $proxy->add_to_capabilities($additional_capabilities);
      $this->session = self::$driver->session('chrome', $additional_capabilities);
      $this->session->open("http://github.com/adamgoucher");
  }

  /**
  * @group proxy
  * @group firefox
  */  
  public function testFirefox() {
      $additional_capabilities = array();
      $proxy = new PHPWebDriver_WebDriverProxy();
      $proxy->httpProxy = self::$client->url;
      $proxy->add_to_capabilities($additional_capabilities);
      $this->session = self::$driver->session('firefox', $additional_capabilities);
      $this->session->open("http://github.com/adamgoucher");
  }

  /**
  * @group proxy
  * @group firefox
  * @group auth
  */  
  public function testAuthentication() {
      $additional_capabilities = array();
      $proxy = new PHPWebDriver_WebDriverProxy();
      $proxy->httpProxy = self::$client->url;
      $proxy->add_to_capabilities($additional_capabilities);
      $this->session = self::$driver->session('firefox', $additional_capabilities);
      
      self::$client->basic_auth('www.httpwatch.com', array('username' => 'httpwatch', 'password' => 'blah'));
      $this->session->open("http://www.httpwatch.com/httpgallery/authentication/authenticatedimage/default.aspx?0.992212271085009");
  
      sleep(3);

      self::$client->headers(array('monkey' => 'butt'));
      $this->session->open("http://www.cylog.org/headers/");

      sleep(4);
  }
}
?>