<?php
// Copyright 2004-present Facebook. All Rights Reserved.
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

require_once('WebDriverBase.php');
require_once('WebDriverSession.php');
require_once('WebDriverDesiredCapabilities.php');

class PHPWebDriver_WebDriver extends PHPWebDriver_WebDriverBase {

  function __construct($executor = null) {
    if (! is_null($executor)) {
      parent::__construct($executor);      
    } else {
      parent::__construct();
    }

  }

  protected function methods() {
    return array(
      'status' => 'GET',
    );
  }

  public function session($browser = 'firefox',
                          $additional_capabilities = array(),
                          $curl_opts = array()) {
    $capabilities = new PHPWebDriver_WebDriverDesiredCapabilities();
    $desired_capabilities = array_merge(
      $capabilities->$browser,
      $additional_capabilities
    );
    // var_dump($desired_capabilities);
    $curl_opts = $curl_opts + array(CURLOPT_FOLLOWLOCATION => true);
      
    $results = $this->curl(
      'POST',
      '/session',
      array('desiredCapabilities' => $desired_capabilities),
      $curl_opts);
    // var_dump($results);
    return new PHPWebDriver_WebDriverSession($results['info']['url']);
  }
  
  public function sessions($curl_opts = array()) {
    $result = $this->curl('GET', '/sessions', null, $curl_opts);
    $sessions = array();
    foreach ($result['value'] as $session) {
      $sessions[] = new PHPWebDriver_WebDriverSession(
        $this->url . '/session/' . $session['id']);
    }
    return $sessions;
  }
}
