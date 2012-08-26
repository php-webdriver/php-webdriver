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

require_once('WebDriverEnvironment.php');
require_once('WebDriverExceptions.php');

abstract class PHPWebDriver_WebDriverBase {
  public static function throwException($status_code, $message, $results) {
      switch ($status_code) {
        case 0:
          // Success
          break;
        case 1:
          throw new PHPWebDriver_IndexOutOfBoundsWebDriverError($message, $results);
        case 2:
          throw new PHPWebDriver_NoCollectionWebDriverError($message, $results);
        case 3:
          throw new PHPWebDriver_NoStringWebDriverError($message, $results);
        case 4:
          throw new PHPWebDriver_NoStringLengthWebDriverError($message, $results);
        case 5:
          throw new PHPWebDriver_NoStringWrapperWebDriverError($message, $results);
        case 6:
          throw new PHPWebDriver_NoSuchDriverWebDriverError($message, $results);
        case 7:
          throw new PHPWebDriver_NoSuchElementWebDriverError($message, $results);
        case 8:
          throw new PHPWebDriver_NoSuchFrameWebDriverError($message, $results);
        case 9:
          throw new PHPWebDriver_UnknownCommandWebDriverError($message, $results);
        case 10:
          throw new PHPWebDriver_ObsoleteElementWebDriverError($message, $results);
        case 11:
          throw new PHPWebDriver_ElementNotDisplayedWebDriverError($message, $results);
        case 12:
          throw new PHPWebDriver_InvalidElementStateWebDriverError($message, $results);
        case 13:
          throw new PHPWebDriver_UnhandledWebDriverError($message, $results);
        case 14:
          throw new PHPWebDriver_ExpectedWebDriverError($message, $results);
        case 15:
          throw new PHPWebDriver_ElementNotSelectableWebDriverError($message, $results);
        case 16:
          throw new PHPWebDriver_NoSuchDocumentWebDriverError($message, $results);
        case 17:
          throw new PHPWebDriver_UnexpectedJavascriptWebDriverError($message, $results);
        case 18:
          throw new PHPWebDriver_NoScriptResultWebDriverError($message, $results);
        case 19:
          throw new PHPWebDriver_XPathLookupWebDriverError($message, $results);
        case 20:
          throw new PHPWebDriver_NoSuchCollectionWebDriverError($message, $results);
        case 21:
          throw new PHPWebDriver_TimeOutWebDriverError($message, $results);
        case 22:
          throw new PHPWebDriver_NullPointerWebDriverError($message, $results);
        case 23:
          throw new PHPWebDriver_NoSuchWindowWebDriverError($message, $results);
        case 24:
          throw new PHPWebDriver_InvalidCookieDomainWebDriverError($message, $results);
        case 25:
          throw new PHPWebDriver_UnableToSetCookieWebDriverError($message, $results);
        case 26:
          throw new PHPWebDriver_UnexpectedAlertOpenWebDriverError($message, $results);
        case 27:
         throw new PHPWebDriver_NoAlertOpenWebDriverError($message, $results);
        case 28:
          throw new PHPWebDriver_TimeOutWebDriverError($message, $results);
        case 29:
          throw new PHPWebDriver_InvalidElementCoordinatesWebDriverError($message, $results);
        case 30:
          throw new PHPWebDriver_IMENotAvailableWebDriverError($message, $results);
        case 31:
          throw new PHPWebDriver_IMEEngineActivationFailedWebDriverError($message, $results);
        case 32:
          throw new PHPWebDriver_InvalidSelectorWebDriverError($message, $results);
        case 500:
          throw new PHPWebDriver_InternalServerError($message, $results);
      }
    }
  abstract protected function methods();

  protected $url;
  public function __construct($url = 'http://localhost:4444/wd/hub') {
    $this->url = $url;
  }


  public function __toString() {
    return $this->url;
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
      throw new Exception(sprintf(
        'The http method called for %s is %s but it has to be POST' .
        ' if you want to pass the JSON params %s',
        $command,
        $http_method,
        json_encode($params)));
    }

    $url = sprintf('%s%s', $this->url, $command);
    if ($params && (is_int($params) || is_string($params))) {
      $url .= '/' . $params;
    }

    $curl = curl_init($url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt(
      $curl,
      CURLOPT_HTTPHEADER,
      array(
        'Content-Type: application/json;charset=UTF-8',
        'Accept: application/json',
        'Expect:')
    );

    if ($http_method === 'POST') {
      curl_setopt($curl, CURLOPT_POST, true);
      if ($params && is_array($params)) {
        // var_dump(json_encode($params));
        curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($params));
      }  else {
        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-length: 0'));
      }
    } else if ($http_method == 'DELETE') {
      curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'DELETE');
    }

    foreach ($extra_opts as $option => $value) {
      curl_setopt($curl, $option, $value);
    }

    $raw_results = trim(PHPWebDriver_WebDriverEnvironment::CurlExec($curl));

    $info = curl_getinfo($curl);
    
    if ($error = curl_error($curl)) {
      $msg = sprintf(
        'Curl error thrown for http %s to %s',
        $http_method,
        $url);
      if ($params && is_array($params)) {
        $msg .= sprintf(' with params: %s', json_encode($params));
      }
      throw new PHPWebDriver_WebDriverCurlException($msg . "\n\n" . $error);
    }
    curl_close($curl);

    $results = json_decode($raw_results, true);

    $value = null;
    if (is_array($results) && array_key_exists('value', $results)) {
      $value = $results['value'];
    }

    $message = null;
    if (is_array($value) && array_key_exists('message', $value)) {
      $message = $value['message'];
    }

    self::throwException($results['status'], $message, $results);
    
    return array('value' => $value, 'info' => $info);
  }

  public function __call($name, $arguments) {
    if (count($arguments) > 2) {
      throw new Exception(
        'Commands should have at most only two parameters,' .
        ' normally just the JSON Parameter object,' .
        ' but sometimes curl options as well (mainly for debugging)');
    }

    if (preg_match('/^(get|post|delete)/', $name, $matches)) {
      $http_method = strtoupper($matches[0]);
      $webdriver_command = strtolower(substr($name, strlen($http_method)));
      $default_http_method = $this->getHTTPMethod($webdriver_command);
      if ($http_method === $default_http_method) {
        throw new Exception(sprintf(
          '%s is the default http method for %s.  Please just call %s().',
          $http_method,
          $webdriver_command,
          $webdriver_command));
      }
      $methods = $this->methods();
      if (!in_array($http_method, $methods[$webdriver_command])) {
        throw new Exception(sprintf(
          '%s is not an available http method for the command %s.',
          $http_method,
          $webdriver_command));
      }
    } else {
      $webdriver_command = $name;
      $http_method = $this->getHTTPMethod($webdriver_command);
    }

    $params = array_shift($arguments);
    $opts = array_shift($arguments);
    if (count($opts) == 0) {
        $opts = array();
    }
    $results = $this->curl(
      $http_method,
      '/' . $webdriver_command,
      $params,
      $opts);

    $value = null;
    if (is_array($results) && array_key_exists('value', $results)) {
      $value = $results['value'];
    }

    $message = null;
    if (is_array($value) && array_key_exists('message', $value)) {
      $message = $value['message'];
    }

    self::throwException($results['info']['http_code'], $message, $results);

    return $results['value'];
  }

  private function getHTTPMethod($webdriver_command) {
    if (!array_key_exists($webdriver_command, $this->methods())) {
      throw new Exception(sprintf(
        '%s is not a valid webdriver command.',
        $webdriver_command));
    }

    $methods = $this->methods();
    $http_methods = (array) $methods[$webdriver_command];
    return array_shift($http_methods);
  }
}
