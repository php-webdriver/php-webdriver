<?php
require_once(dirname(__FILE__) . '/../../PHPWebDriver/WebDriver.php');
require_once(dirname(__FILE__) . '/../../PHPWebDriver/Support/WebDriverSelect.php');
 
class SelectTest extends PHPUnit_Framework_TestCase {
  protected static $session;
  
  public static function setUpBeforeClass() {
      $driver = new PHPWebDriver_WebDriver();
      self::$session = $driver->session(); // firefox
      self::$session->open("http://localhost:8000");
  }

  public static function tearDownAfterClass() {
      self::$session->close();
  }
  
  /**
  * @test
  * @group select
  * @expectedException PHPWebDriver_UnexpectedTagNameException
  */
  public function not_a_select() {
      $e = self::$session->element('id', 'header');
      $s = new PHPWebDriver_Support_WebDriverSelect($e);
  }

  /**
  * @test
  * @group select
  */
  public function single_options() {
      $e = self::$session->element('id', 'single');
      $s = new PHPWebDriver_Support_WebDriverSelect($e);
      $this->assertEquals(count($s->options), 4);
  }
  
  /**
  * @test
  * @group select
  * @expectedException PHPWebDriver_NoSuchElementWebDriverError
  */
  public function single_no_option_by_value() {
      $e = self::$session->element('id', 'single');
      $s = new PHPWebDriver_Support_WebDriverSelect($e);
      $s->select_by_value("Ha");
  }

  /**
  * @test
  * @group select
  */
  public function single_all_selected_options() {
      $e = self::$session->element('id', 'single');
      $s = new PHPWebDriver_Support_WebDriverSelect($e);
      $s->select_by_value("Gi");
      $this->assertEquals(count($s->all_selected_options), 1);
      $this->assertEquals($s->all_selected_options[0]->text(), "Gibbon");
  }

  /**
  * @test
  * @group select
  */
  public function single_first_selected_value() {
      $e = self::$session->element('id', 'single');
      $s = new PHPWebDriver_Support_WebDriverSelect($e);
      $s->select_by_value("Gi");
      $this->assertEquals($s->first_selected_value->text(), "Gibbon");
  }

  /**
  * @test
  * @group select
  * @expectedException PHPWebDriver_NoSuchElementWebDriverError
  */
  public function single_no_option_by_index() {
      $e = self::$session->element('id', 'single');
      $s = new PHPWebDriver_Support_WebDriverSelect($e);
      $s->select_by_index(53);
  }

  /**
  * @test
  * @group select
  */
  public function single_select_by_index() {
      $e = self::$session->element('id', 'single');
      $s = new PHPWebDriver_Support_WebDriverSelect($e);
      $s->select_by_index(1);
      $this->assertEquals($s->first_selected_value->text(), "Gorilla");
  }

  /**
  * @test
  * @group select
  */
  public function single_select_by_visible_text() {
      $e = self::$session->element('id', 'single');
      $s = new PHPWebDriver_Support_WebDriverSelect($e);
      $s->select_by_visible_text("Gibbon");
      $this->assertEquals($s->first_selected_value->text(), "Gibbon");
  }

  /**
  * @test
  * @group select
  */
  public function single_select_by_visible_text_with_blank() {
      $e = self::$session->element('id', 'single');
      $s = new PHPWebDriver_Support_WebDriverSelect($e);
      $s->select_by_value("DM");
      $s->select_by_visible_text(" ");
      $this->assertEquals($s->first_selected_value->text(), "Monkey");
  }

  /**
  * @test
  * @group select
  */
  public function single_select_by_visible_text_contains() {
      $e = self::$session->element('id', 'single');
      $s = new PHPWebDriver_Support_WebDriverSelect($e);
      $s->select_by_value("M");
      $s->select_by_visible_text("Dancing M");
      $this->assertEquals($s->first_selected_value->text(), "Dancing Monkey");
  }

  /**
  * @test
  * @group select
  */
  public function multiple_deselect_all() {
      $e = self::$session->element('id', 'multiple');
      $s = new PHPWebDriver_Support_WebDriverSelect($e);
      $s->select_by_visible_text("Monkey");
      $s->select_by_visible_text("Dancing Monkey");
      $this->assertEquals(count($s->all_selected_options), 2);
      $s->deselect_all();
      $this->assertEquals(count($s->all_selected_options), 0);
  }

  /**
  * @test
  * @group select
  */
  public function multiple_deselect_by_value() {
      $e = self::$session->element('id', 'multiple');
      $s = new PHPWebDriver_Support_WebDriverSelect($e);
      $s->select_by_visible_text("Monkey");
      $s->select_by_visible_text("Dancing Monkey");
      $this->assertEquals(count($s->all_selected_options), 2);
      $s->deselect_by_value('DM');
      $this->assertEquals(count($s->all_selected_options), 1);
      $this->assertEquals($s->first_selected_value->text(), "Monkey");
  }

  /**
  * @test
  * @group select
  */
  public function multiple_deselect_by_index() {
      $e = self::$session->element('id', 'multiple');
      $s = new PHPWebDriver_Support_WebDriverSelect($e);
      $s->select_by_visible_text("Monkey");
      $s->select_by_visible_text("Dancing Monkey");
      $this->assertEquals(count($s->all_selected_options), 2);
      $s->deselect_by_index(0);
      $this->assertEquals(count($s->all_selected_options), 1);
      $this->assertEquals($s->first_selected_value->text(), "Dancing Monkey");
  }

  /**
  * @test
  * @group select
  */
  public function multiple_deselect_by_visible_text() {
      $e = self::$session->element('id', 'multiple');
      $s = new PHPWebDriver_Support_WebDriverSelect($e);
      $s->select_by_visible_text("Monkey");
      $s->select_by_visible_text("Dancing Monkey");
      $this->assertEquals(count($s->all_selected_options), 2);
      $s->deselect_by_visible_text("Monkey");
      $this->assertEquals(count($s->all_selected_options), 1);
      $this->assertEquals($s->first_selected_value->text(), "Dancing Monkey");
  }

      // $this->assertEquals($s->all_selected_options[0]->text(), "Monkey");
      // $this->assertEquals($s->all_selected_options[1]->text(), "Dancing Monkey");
}
