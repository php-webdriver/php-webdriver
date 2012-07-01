<?php

require_once(dirname(__FILE__) . '/../../WebDriverWait.php');

class PHPWebDriver_WebDriver_Support_FlashFlex_FlexPilot {
  public function __construct($session, $movie) {
    $this->session = $session;
    $this->movie = $movie;
  }
  
  public function is_flex_ready() {
    $r = $this->session->execute(array(
                                    "script" => 'return typeof(arguments[0].fp_click);',
                                    "args" => array(array("ELEMENT" => $this->movie->getID()))
                                    )
                                );
    if ($r == "function") {
      return True;
    }
    return False;
  }
  
  public function wait_for_flex_ready($timeout = 30, $poll_frequency = 0.5) {
    $w = new PHPWebDriver_WebDriverWait($this->session, $timeout, $poll_frequency, array("movie" => $this->movie));
    $e = $w->until(
            function($session, $extra_arguments) {
              $fp = new PHPWebDriver_WebDriver_Support_FlashFlex_FlexPilot($session, $extra_arguments["movie"]);
              return $fp->is_flex_ready();
            }
         );
  }
}