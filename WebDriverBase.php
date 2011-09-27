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

abstract class WebDriverBase {
  abstract protected function methods();

  protected $url;
  public function __construct($url = 'http://localhost:4444/wd/hub') {
    $this->url = $url;
  }

  public function getURL() {
    return $this->url;
  }

  /**
   * Curl request to webdriver server.
   *
   * $http_method  'GET', 'POST', or 'DELETE'
   * $command      If not defined in methods() this function will throw.
   * $params       If an array(), they will be posted as JSON parameters
   *               If a number or string, "/$params" is appended to url
   * $extra_opts   key=>value pairs of curl options to pass to curl_setopt()
   */
  protected function curl($http_method,
                          $command,
                          $params = null,
                          $extra_opts = array()) {
    if ($params && is_array($params) && $http_method !== 'POST') {
      throw(new Exception(sprintf(
        'The http method called for %s is %s but it has to be POST' .
        ' if you want to pass the JSON params %s',
        $command,
        $http_method,
        json_encode($params))));
    }

    $url = sprintf('%s%s', $this->url, $command);
    if ($params && (is_int($params) || is_string($params))) {
      $url .= '/' . $params;
    }

    $curl = curl_init($url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_HTTPHEADER,
                array('application/json;charset=UTF-8'));

    if ($http_method === 'POST') {
      curl_setopt($curl, CURLOPT_POST, true);
      if ($params && is_array($params)) {
        curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($params));
      }
    } else if ($http_method == 'DELETE') {
      curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'DELETE');
    }

    foreach ($extra_opts as $option => $value) {
      curl_setopt($curl, $option, $value);
    }

    $raw_results = trim(WebDriverEnvironment::CurlExec($curl));
    $results = json_decode($raw_results, true);

    $info = curl_getinfo($curl);
    if ($error = curl_error($curl)) {
      throw(new Exception(sprintf(
        'Curl error for request %s: %s',
        $url,
        $error)));
    }
    curl_close($curl);

    $value = null;
    if (is_array($results) && array_key_exists('value', $results)) {
      $value = $results['value'];
    }

    return array('value' => $value, 'info' => $info);
  }

  public function __call($name, $arguments) {
    if (count($arguments) > 1) {
      throw(new Exception(
        'Commands should have at most only one parameter,' .
        ' which should be the JSON Parameter object'));
    }

    if (preg_match('/^(get|post|delete)/', $name, $matches)) {
      $http_method = strtoupper($matches[0]);
      $webdriver_command = strtolower(substr($name, strlen($http_method)));
      $default_http_method = $this->getHTTPMethod($webdriver_command);
      if ($http_method === $default_http_method) {
        throw(new Exception(sprintf(
          '%s is the default http method for %s.  Please just call %s().',
          $http_method,
          $webdriver_command,
          $webdriver_command)));
      }
      $methods = $this->methods();
      if (!in_array($http_method, $methods[$webdriver_command])) {
        throw(new Exception(sprintf(
          '%s is not an available http method for the command %s.',
          $http_method,
          $webdriver_command)));
      }
    } else {
      $webdriver_command = $name;
      $http_method = $this->getHTTPMethod($webdriver_command);
    }

    $results = $this->curl($http_method,
                           '/' . $webdriver_command,
                           array_shift($arguments));

    return $results['value'];
  }

  private function getHTTPMethod($webdriver_command) {
    if (!array_key_exists($webdriver_command, $this->methods())) {
      throw(new Exception(sprintf(
        '%s is not a valid webdriver command.',
        $webdriver_command)));
    }

    $methods = $this->methods();
    $http_methods = (array) $methods[$webdriver_command];
    return array_shift($http_methods);
  }
}
