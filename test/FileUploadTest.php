<?php
require_once(dirname(__FILE__) . '/../PHPWebDriver/WebDriver.php');
require_once(dirname(__FILE__) . '/../PHPWebDriver/WebDriverBy.php');
require_once(dirname(__FILE__) . '/../PHPWebDriver/Support/WebDriverSelect.php');
require_once(dirname(__FILE__) . '/../PHPWebDriver/WebDriverWait.php');
 
class FileUploadTest extends PHPUnit_Framework_TestCase {
  protected static $session;

  private $locators = array(
    'upload' => array(\PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'input[name="upload"]'),
    'button' => array(\PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'input[type="submit"]'),
    'storetime' => array(\PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'select[name="storetime"]'),
    'obscure_filename' => array(\PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'select[name="addprivacy"]'),
    'accept_rules' => array(\PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'select[name="rules"]'),
    'image' => array(\PHPWebDriver_WebDriverBy::CSS_SELECTOR, '.picture img[src^="/extpics"]:not([alt="new"])'),
  );
  
  public static function setUpBeforeClass() {
    $driver = new PHPWebDriver_WebDriver();
    self::$session = $driver->session(); // firefox
  }
  
  public static function tearDownAfterClass() {
    self::$session->close();
  }
  
  /**
  * @group upload
  */
  public function test_file_exists_full_path() {
    self::$session->open("http://picpaste.com/");

    $my_file = dirname(__FILE__) . '/english_muffin.jpg';
    $e = call_user_func_array(array(self::$session, "element"), $this->locators['upload']);
    $e->sendKeys($my_file);

    $st = call_user_func_array(array(self::$session, "element"), $this->locators['storetime']);
    $s = new PHPWebDriver_Support_WebDriverSelect($st);
    $s->select_by_visible_text('30 Minutes');

    $of = call_user_func_array(array(self::$session, "element"), $this->locators['obscure_filename']);
    $s = new PHPWebDriver_Support_WebDriverSelect($of);
    $s->select_by_visible_text('basic');

    $ar = call_user_func_array(array(self::$session, "element"), $this->locators['accept_rules']);
    $s = new PHPWebDriver_Support_WebDriverSelect($ar);
    $s->select_by_visible_text('Yes');

    $b = call_user_func_array(array(self::$session, "element"), $this->locators['button']);
    $b->click();

    $w = new \PHPWebDriver_WebDriverWait(self::$session, 15, 0.5, array("locator" => $this->locators['image']));
    $w->until(
      function($session, $extra_arguments) {
        return call_user_func_array(array($session, "element"), $extra_arguments['locator']);
      }
    );
  }

}
?>