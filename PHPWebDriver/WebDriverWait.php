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

class PHPWebDriver_WebDriverWait {
    public function __construct($session, $timeout = 30, $poll_frequency = 0.5, $extra_arguments = array()) {
      $this->session = $session;
      $this->timeout = $timeout;
      $this->poll = $poll_frequency;
      $this->extra_arguments = $extra_arguments;
      return $this;
    }
    
    public function until($func) {
        $end_time = time() + $this->timeout;
        while(time() < $end_time) {
            try {
                $value = call_user_func($func, $this->session, $this->extra_arguments);
                if ($value) {
                  return $value;
                }
            } catch (PHPWebDriver_NoSuchElementWebDriverError $e) {

            }
            usleep(intval($this->poll * 1000000));
        }
        throw new PHPWebDriver_TimeOutWebDriverError(
          sprintf(
            'Element wait timed out after %s',
            $this->timeout) . "\n\n");
    }
}

?>