<?php
// Copyright 2011-present Element 34
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

include_once('WebDriverExceptions.php');

class PHPWebDriver_WebDriverActionChains {
  private $session;
  private $actions = array();

  public function __construct($session) {
    $this->session = $session;
    return $this;
  }
  
  public function perform() {
    foreach ($this->actions as $action) {
      eval($action);
    }
  }
  
  public function click($onElement = null) {}

  public function clickAndHold($onElement) {}
    
  public function contextClick($onElement) {}
    
  public function doubleClick($onElement) {}
    
  public function dragAndDrop($source, $target) {}
    
  public function dragAndDropByOffset($source, $target, $xOffset, $yOffset) {}
    
  public function keyDown($value, $onElement = null) {}
    
  public function keyUp($value, $onElement = null) {}

  public function moveByOffset($source, $xOffset, $yOffset) {}
        
  public function moveToElement($toElement) {
    $this->actions[] = '$this->session->moveto(array("element" => "' . $toElement->getID() . '"));';
    return $this;
  }  

  public function moveToElementWithOffset($toElement, $xOffset, $yOffset) {}

  public function release($onElement) {}
    
  public function sendKeys($keysToSend) {}
    
  public function sendKeysToElement($toElement, $keysToSend) {}
}

?>