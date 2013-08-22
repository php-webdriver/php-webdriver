<?php
require_once(dirname(__FILE__) . '/../PHPWebDriver/WebDriver.php');
require_once(dirname(__FILE__) . '/../PHPWebDriver/WebDriverActionChains.php');
require_once(dirname(__FILE__) . '/../PHPWebDriver/WebDriverBy.php');
require_once(dirname(__FILE__) . '/../PHPWebDriver/WebDriverKeys.php');

class ActionChainsTest extends PHPUnit_Framework_TestCase {
  protected static $session;
  
  public function setUp() {
    $driver = new PHPWebDriver_WebDriver();
    self::$session = $driver->session('chrome');
  }
  
  public function tearDown() {
    self::$session->close();
  }

  /**
  * @group chains
  */  
  public function testDragAndDrop() {
    self::$session->open("http://jqueryui.com/droppable");
    $iframe = self::$session->element(PHPWebDriver_WebDriverBy::CSS_SELECTOR, ".demo-frame");
    self::$session->switch_to_frame($iframe);

    $draggable = self::$session->element(PHPWebDriver_WebDriverBy::ID, "draggable");
    $droppable = self::$session->element(PHPWebDriver_WebDriverBy::ID, "droppable");

    $chain = new PHPWebDriver_WebDriverActionChains(self::$session);
    $chain->dragAndDrop($draggable, $droppable);
    $chain->perform();
    
    $dropped = self::$session->element(PHPWebDriver_WebDriverBy::ID, "droppable");
    $this->AssertEquals($dropped->text(), 'Dropped!');
  }

  /**
  * @group chains
  */  
  public function testRightClick() {
    self::$session->open("http://www.javascripttoolbox.com/lib/contextmenu");

    $default_theme = self::$session->element(PHPWebDriver_WebDriverBy::XPATH, '//div[text()="Default Theme"]');

    $chain = new PHPWebDriver_WebDriverActionChains(self::$session);
    $chain->contextClick($default_theme);
    $chain->perform();

    // this needs a better page, and an assert
  }

  /**
  * @group chains
  */  
  public function testDragAndDropByOffset() {
    self::$session->open("http://jqueryui.com/droppable");
    $iframe = self::$session->element(PHPWebDriver_WebDriverBy::CSS_SELECTOR, ".demo-frame");
    self::$session->switch_to_frame($iframe);

    $draggable = self::$session->element(PHPWebDriver_WebDriverBy::ID, "draggable");
    $droppable = self::$session->element(PHPWebDriver_WebDriverBy::ID, "droppable");

    $chain = new PHPWebDriver_WebDriverActionChains(self::$session);
    $chain->dragAndDropByOffset($draggable, $droppable, 450, -15);
    $chain->perform();
    
    $dropped = self::$session->element(PHPWebDriver_WebDriverBy::ID, "droppable");
    $this->AssertEquals($dropped->text(), 'Dropped!');
  }

  /**
  * @group chains
  */  
  public function testTypingWithModifiers() {
    self::$session->open("http://pastebin.com/");

    $paste = self::$session->element(PHPWebDriver_WebDriverBy::ID, "paste_code");
    $paste->click();

    $chain = new PHPWebDriver_WebDriverActionChains(self::$session);
    $chain->keyDown(new PHPWebDriver_WebDriverKeys('ShiftKey'), array(CURLOPT_VERBOSE => true));
    $chain->sendKeys('monkey');
    $chain->keyUp(new PHPWebDriver_WebDriverKeys('ShiftKey'));
    $chain->sendKeys(' butt!');
    $chain->perform();
  }

  /**
  * @group chains
  * @group elephant
  */  
  public function testTypingAtElement() {
    self::$session->open("http://pastebin.com/");

    $paste = self::$session->element(PHPWebDriver_WebDriverBy::ID, "paste_code");

    $chain = new PHPWebDriver_WebDriverActionChains(self::$session);
    $chain->sendKeysToElement($paste, 'monkey');
    $chain->perform();

    sleep(3);
  }


}
?>