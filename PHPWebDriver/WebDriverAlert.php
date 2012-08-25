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

require_once(dirname(__FILE__) . '/WebDriverSession.php');

class PHPWebDriver_WebDriverAlert extends PHPWebDriver_WebDriverSession {
    public function __construct($session) {
          $this->session = $session;
          return $this;
    }
    
    function __get($property) {
        switch($property) {
            // text
            case "text":
                $result = $this->session->curl('GET', '/alert_text', '', array());
                return $result['value'];
            default:
                if (isset($property)) {
                    return $this->$property;
                }
        }
    }
    
    public function accept() {
        $this->session->accept_alert();
    }

    public function dismiss() {
        $this->session->dismiss_alert();
    }

    public function sendKeys($keys) {
        $result = $this->session->curl('POST', '/alert_text', array('text' => $keys), array());
    }
}
