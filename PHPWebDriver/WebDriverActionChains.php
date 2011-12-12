<?php

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