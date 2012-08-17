<?php
// Copyright 2012-present Element 34
//
// Licensed under the Apache License, Version 2.0 (the "License");
// you may not use this file except in compliance with the License.
// You may obtain a copy of the License at
//
//     http://www.apache.org/licenses/LICENSE-2.0
//
// Unless required by applicable law or agreed to in writing, software
// distributed under the License is distributed on an "AS IS" BASIS,
// WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
// See the License for the specific language governing permissions and
// limitations under the License.

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
  
  public function is_flex_object($chain) {
    $options = array("chain" => $chain);
    return $this->session->execute(array(
                                      "script" => 'return arguments[0].fp_assertDisplayObject(arguments[1]);',
                                      "args" => array(array("ELEMENT" => $this->movie->getID()),
                                                      $options)
                                      )
                                  );

  }
  
  public function wait_for_object($chain, $timeout = 30, $poll_frequency = 0.5) {
    $w = new PHPWebDriver_WebDriverWait($this->session, $timeout, $poll_frequency, array("movie" => $this->movie, "chain" => $chain));
    $e = $w->until(
            function($session, $extra_arguments) {
              $fp = new PHPWebDriver_WebDriver_Support_FlashFlex_FlexPilot($session, $extra_arguments["movie"]);
              return $fp->is_flex_object($extra_arguments["chain"]);
            }
         );    
  }
  
  public function sendKeys($chain, $text) {
    return $this->send_keys($chain, $text);
  }
  
  public function send_keys($chain, $text) {
    $options = array("chain" => $chain, "text" => $text);
    $r = $this->session->execute(array(
                                    "script" => 'arguments[0].fp_type(arguments[1]);',
                                    "args" => array(array("ELEMENT" => $this->movie->getID()),
                                                    $options)
                                    ));
  }

  public function click($chain) {
    $options = array("chain" => $chain);
    $r = $this->session->execute(array(
                                    "script" => 'return arguments[0].fp_click(arguments[1]);',
                                    "args" => array(array("ELEMENT" => $this->movie->getID()),
                                                    $options)
                                    ));    
  }
  
  public function double_click($chain) {
    $options = array("chain" => $chain);
    $r = $this->session->execute(array(
                                    "script" => 'return arguments[0].fp_doubleClick(arguments[1]);',
                                    "args" => array(array("ELEMENT" => $this->movie->getID()),
                                                    $options)
                                    ));    
  }
  
  public function text($chain) {
    $options = array("chain" => $chain);
    return $this->session->execute(array(
                                        "script" => 'return arguments[0].fp_getTextValue(arguments[1]);',
                                        "args" => array(array("ELEMENT" => $this->movie->getID()),
                                                        $options)
                                        ));
  }

  public function attribute($chain, $property) {
    $options = array("chain" => $chain, "attrName" => $property);
    return $this->session->execute(array(
                                        "script" => 'return arguments[0].fp_getPropertyValue(arguments[1]);',
                                        "args" => array(array("ELEMENT" => $this->movie->getID()),
                                                        $options)
                                        ));    
  }
  
  public function date() {}
    
  public function select() {}

}
