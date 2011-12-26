<?php

include_once('WebDriverExceptions.php');

class PHPWebDriver_WebDriverWait {
    public function __construct($session, $timeout = 30, $poll_frequency = 0.5) {
      $this->session = $session;
      $this->timeout = $timeout;
      $this->poll = $poll_frequency;
      return $this;
    }
    
    public function until($func) {
        $end_time = time() + $this->timeout;
        while($time < $end_time) {
            try {
                $value = call_user_func($func, $this->session);
                if ($value) {
                  return $value;
                }
            } catch (PHPWebDriver_NoSuchElementWebDriverError $e) {

            }
            sleep($this->poll);
        }
        throw new PHPWebDriver_TimeOutWebDriverError(
          sprintf(
            'Element wait timed out after %s',
            $this->timeout) . "\n\n");
    }
}

?>