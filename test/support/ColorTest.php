<?php
require_once(dirname(__FILE__) . '/../../PHPWebDriver/Support/WebDriverColor.php');
 
class LocationTest extends PHPUnit_Framework_TestCase {
  protected static $session;
  
  /**
  * @test
  * @group color
  */
  public function rgbToRgb() {
      $rgb = "rgb(1, 2, 3)";
      $c = new PHPWebDriver_Support_Color($rgb);
      $this->assertEquals($rgb, $c->rgb());
  }
  
  /**
  * @test
  * @group color
  */
  public function rgbToRgba() {
      $rgb = "rgb(1, 2, 3)";
      $c = new PHPWebDriver_Support_Color($rgb);
      $this->assertEquals("rgba(1, 2, 3, 1)", $c->rgba());
  }
  
  /**
  * @test
  * @group color
  */
  public function rgbPctToRgba() {
      $rgba = "rgb(10%, 20%, 30%)";
      $c = new PHPWebDriver_Support_Color($rgba);
      $this->assertEquals("rgba(25, 51, 76, 1)", $c->rgba());
  }
  
  /**
  * @test
  * @group color
  */
  public function rgbAllowsWhitespace() {
      $rgb = "rgb(\t1,   2    , 3)";
      $canonicalRgb = "rgb(1, 2, 3)";
      $c = new PHPWebDriver_Support_Color($rgb);
      $this->assertEquals($canonicalRgb, $c->rgb());
  }
  
  /**
  * @test
  * @group color
  */
  public function rgbaToRgba() {
      $rgba = "rgba(1, 2, 3, 0.5)";
      $c = new PHPWebDriver_Support_Color($rgba);
      $this->assertEquals($rgba, $c->rgba());
  }
  
  /**
  * @test
  * @group color
  */
  public function rgbaPctToRgba() {
      $rgba = "rgba(10%, 20%, 30%, 0.5)";
      $c = new PHPWebDriver_Support_Color($rgba);
      $this->assertEquals("rgba(25, 51, 76, 0.5)", $c->rgba());
  }
  
  /**
  * @test
  * @group color
  */
  public function hexToHex() {
      $hex = "#ff00a0";
      $c = new PHPWebDriver_Support_Color($hex);
      $this->assertEquals($hex, $c->hex());
  }
  
  /**
  * @test
  * @group color
  */
  public function hexToRgb() {
      $hex = "#01Ff03";
      $rgb = "rgb(1, 255, 3)";
      $c = new PHPWebDriver_Support_Color($hex);
      $this->assertEquals($rgb, $c->rgb());
  }
  
  /**
  * @test
  * @group color
  */
  public function hexToRgba() {
      $hex = "#01Ff03";
      $rgba = "rgba(1, 255, 3, 1)";
      $c = new PHPWebDriver_Support_Color($hex);
      $this->assertEquals($rgba, $c->rgba());

      // same test data as hex3 below
      $hex = "#00ff33";
      $rgba = "rgba(0, 255, 51, 1)";
      $c = new PHPWebDriver_Support_Color($hex);
      $this->assertEquals($rgba, $c->rgba());
  }
  
  /**
  * @test
  * @group color
  */
  public function rgbToHex() {
      $hex = "#01ff03";
      $rgb = "rgb(1, 255, 3)";
      $c = new PHPWebDriver_Support_Color($rgb);
      $this->assertEquals($hex, $c->hex());
  }
  
  /**
  * @test
  * @group color
  */
  public function hex3ToRgba() {
      $hex = "#0f3";
      $rgba = "rgba(0, 255, 51, 1)";
      $c = new PHPWebDriver_Support_Color($hex);
      $this->assertEquals($rgba, $c->rgba());
  }
  
  /**
  * @test
  * @group color
  */
  public function hslToRgba() {
      $hsl = "hsl(120, 100%, 25%)";
      $rgba = "rgba(0, 128, 0, 1)";
      $c = new PHPWebDriver_Support_Color($hsl);
      $this->assertEquals($rgba, $c->rgba());

      $hsl = "hsl(100, 0%, 50%)";
      $rgba = "rgba(128, 128, 128, 1)";
      $c = new PHPWebDriver_Support_Color($hsl);
      $this->assertEquals($rgba, $c->rgba());
  }
  
  /**
  * @test
  * @group color
  */
  public function hslaToRgba() {
      $hsla = "hsla(120, 100%, 25%, 1)";
      $rgba = "rgba(0, 128, 0, 1)";
      $c = new PHPWebDriver_Support_Color($hsla);
      $this->assertEquals($rgba, $c->rgba());

      $hsla = "hsla(100, 0%, 50%, 0.5)";
      $rgba = "rgba(128, 128, 128, 0.5)";
      $c = new PHPWebDriver_Support_Color($hsla);
      $this->assertEquals($rgba, $c->rgba());
  }
}
