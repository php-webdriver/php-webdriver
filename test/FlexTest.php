<?php
require_once(dirname(__FILE__) . '/../PHPWebDriver/WebDriver.php');
require_once(dirname(__FILE__) . '/../PHPWebDriver/WebDriverWait.php');
require_once(dirname(__FILE__) . '/../PHPWebDriver/Support/FlashFlex/FlexPilot.php');

class FlexTest extends PHPUnit_Framework_TestCase {
  protected static $session;
  protected static $fp;
  
  public static function setUpBeforeClass() {
    $driver = new PHPWebDriver_WebDriver();
    self::$session = $driver->session(); // firefox
    self::$session->open("http://localhost:8000/flexstore/flexstore.html");
    $e = self::$session->element("name", "flexstore");
    self::$fp = new PHPWebDriver_WebDriver_Support_FlashFlex_FlexPilot(self::$session, $e);
    self::$fp->wait_for_flex_ready();  

  }
  
  public static function tearDownAfterClass() {
    self::$session->close();
  }
  
  public function test_partially_converted_flexstore_script() {
    $chain = "id:flexstore/name:VBox4/id:storeViews/id:homeView/name:HBox11/name:VBox12/name:Canvas13/name:Canvas14/name:TextInput20/name:UITextField22";
    self::$fp->sendKeys($chain, "nokia");

    $chain = "id:flexstore/name:VBox4/id:storeViews/id:homeView/name:HBox11/name:VBox12/name:Canvas13/name:Canvas14/name:Button18";
    self::$fp->click($chain);

    $chain = "name:OK";
    self::$fp->wait_for_object($chain, 5);
    self::$fp->click($chain);
   
    $chain = "id:flexstore/name:VBox*/id:storeViews/id:homeView/name:HBox11/name:VBox12/name:Canvas13/name:Canvas54/name:VBox*/name:HBox*/name:TextInput*/name:UITextField*";
    self::$fp->click($chain);

    $chain = "id:flexstore/name:VBox*/id:storeViews/id:homeView/name:HBox11/name:VBox12/name:Canvas13/name:Canvas54/name:VBox*/name:HBox*/name:TextInput*/name:UITextField*";
    self::$fp->sendKeys($chain, "360");

    $chain = "id:flexstore/name:VBox*/id:storeViews/id:homeView/name:HBox*/name:VBox*/name:Canvas*/name:Canvas*/name:VBox*/name:TextInput*/name:UITextField*";
    self::$fp->sendKeys($chain, "testing");

    $chain = "id:flexstore/name:VBox*/id:storeViews/id:homeView/name:HBox*/name:VBox*/name:Canvas*/name:Canvas*/name:VBox*/name:Button*";
    self::$fp->click($chain);

    $chain = "name:OK";
    self::$fp->wait_for_object($chain, 5);
    self::$fp->click($chain);

    $chain = "id:flexstore/name:VBox4/id:acb/id:_flexstore_ToggleButtonBar1/label:Products";
    self::$fp->click($chain);

    $chain = "id:flexstore/name:VBox4/id:storeViews/id:pView/name:HBox125/id:filterPanel/name:HBox134/name:TextInput135/name:UITextField137";
    self::$fp->sendKeys($chain, "20");

    $chain = "id:flexstore/name:VBox4/id:storeViews/id:pView/name:HBox125/id:filterPanel/name:HBox134/name:Button138";
    self::$fp->click($chain);
    
    $chain = "name:OK";
    self::$fp->wait_for_object($chain, 5);
    self::$fp->click($chain);
  }
}

?>