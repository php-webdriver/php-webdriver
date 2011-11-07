<?php

include_once('WebDriverExceptions.php');

class PHPWebDriver_WebDriverWait {
    public function __construct($driver, $timeout = 30, $poll_frequency = 0.5) {
      $this->driver = $driver;
      $this->timeout = $timeout;
      $this->poll = $poll_frequency;
      return $this;
    }
    
    public function until($func) {
        foreach (range(0, max(1, $this->timeout/$this->poll)) as $iter) {
            try {
                return call_user_func($func, $this->driver);
            } catch (PHPWebDriver_NoSuchElementWebDriverError $e) {

            }
            sleep($this->poll);
        }
        throw new PHPWebDriver_TimeOutWebDriverError();
    }
}

?>