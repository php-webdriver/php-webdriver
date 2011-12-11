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
  
  public function move_to_element($to_element) {
    $this->actions[] = '$this->session->moveto(array("element" => "' . $to_element->getID() . '"));';
    return $this;
  }  
}

?>