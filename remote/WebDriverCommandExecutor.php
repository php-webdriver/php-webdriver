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

class WebDriverCommandExecutor {

  /**
   * @see
   *   http://code.google.com/p/selenium/wiki/JsonWireProtocol#Command_Reference
   */
  private $commands = array(
    'addCookie' =>         array('method' => 'POST', 'url' => '/session/:sessionId/cookie'),
    'clear' =>             array('method' => 'POST', 'url' => '/session/:sessionId/element/:id/clear'),
    'clickElement' =>      array('method' => 'POST', 'url' => '/session/:sessionId/element/:id/click'),
    'deleteAllCookies' =>  array('method' => 'DELETE',  'url' => '/session/:sessionId/cookie'),
    'deleteCookie' =>      array('method' => 'DELETE',  'url' => '/session/:sessionId/cookie/:name'),
    'executeScript' =>     array('method' => 'POST', 'url' => '/session/:sessionId/execute'),
    'elementFindElement' => array('method' => 'POST', 'url' => '/session/:sessionId/element/:id/element'),
    'elementFindElements' => array('method' => 'POST', 'url' => '/session/:sessionId/element/:id/elements'),
    'findElement' =>       array('method' => 'POST', 'url' => '/session/:sessionId/element'),
    'findElements' =>      array('method' => 'POST', 'url' => '/session/:sessionId/elements'),
    'get' =>               array('method' => 'POST', 'url' => '/session/:sessionId/url'),
    'getAllCookies' =>      array('method' => 'GET',  'url' => '/session/:sessionId/cookie'),
    'getElementAttribute' => array('method' => 'GET',  'url' => '/session/:sessionId/element/:id/attribute/:name'),
    'getElementCSSValue' => array('method' => 'GET',  'url' => '/session/:sessionId/element/:id/css/:propertyName'),
    'getElementLocation' => array('method' => 'GET',  'url' => '/session/:sessionId/element/:id/location'),
    'getElementSize' =>    array('method' => 'GET',  'url' => '/session/:sessionId/element/:id/size'),
    'getCurrentURL' =>     array('method' => 'GET',  'url' => '/session/:sessionId/url'),
    'getElementTagName' => array('method' => 'GET',  'url' => '/session/:sessionId/element/:id/name'),
    'getElementText' =>    array('method' => 'GET',  'url' => '/session/:sessionId/element/:id/text'),
    'getPageSource' =>     array('method' => 'GET',  'url' => '/session/:sessionId/source'),
    'getSession' =>        array('method' => 'GET',  'url' => '/session/:sessionId'),
    'getTitle' =>          array('method' => 'GET',  'url' => '/session/:sessionId/title'),
    'getWindowPosition' => array('method' => 'GET',  'url' => '/session/:sessionId/window/:windowHandle/position'),
    'getWindowSize' =>     array('method' => 'GET',  'url' => '/session/:sessionId/window/:windowHandle/size'),
    'goBack' =>            array('method' => 'POST',  'url' => '/session/:sessionId/back'),
    'goForward' =>         array('method' => 'POST',  'url' => '/session/:sessionId/forward'),
    'isElementDisplayed'=> array('method' => 'GET',  'url' => '/session/:sessionId/element/:id/displayed'),
    'isElementEnabled'=>   array('method' => 'GET',  'url' => '/session/:sessionId/element/:id/enabled'),
    'isElementSelected'=>  array('method' => 'GET',  'url' => '/session/:sessionId/element/:id/selected'),
    'maximizeWindow' =>    array('method' => 'POST', 'url' => '/session/:sessionId/window/:windowHandle/maximize'),
    'newSession' =>        array('method' => 'POST', 'url' => '/session'),
    'refreshPage' =>       array('method' => 'POST', 'url' => '/session/:sessionId/refresh'),
    'setImplicitWaitTimeout' => array('method' => 'POST', 'url' => '/session/:sessionId/timeouts/implicit_wait'),
    'setPageLoadTimeout' => array('method' => 'POST', 'url' => '/session/:sessionId/timeouts'),
    'setScriptTimeout' =>  array('method' => 'POST', 'url' => '/session/:sessionId/timeouts/async_script'),
    'setWindowPosition' => array('method' => 'POST', 'url' => '/session/:sessionId/window/:windowHandle/position'),
    'setWindowSize' =>     array('method' => 'POST', 'url' => '/session/:sessionId/window/:windowHandle/size'),
    'quit' =>              array('method' => 'DELETE', 'url' => '/session/:sessionId'),
    'sendKeysToElement' => array('method' => 'POST', 'url' => '/session/:sessionId/element/:id/value'),
    'submitElement' =>     array('method' => 'POST', 'url' => '/session/:sessionId/element/:id/submit'),
  );

  protected $url;

  public function __construct($url) {
    $this->url = $url;
  }

  public function execute($command) {
    if (!isset($this->commands[$command['name']])) {
      throw new Exception($command['name']." is not a valid command.");
    }
    $raw = $this->commands[$command['name']];
    $extra_opts = array();

    if ($command['name'] == 'newSession') {
      $extra_opts[CURLOPT_FOLLOWLOCATION] = true;
    }

    return $this->curl($raw['method'], $raw['url'], $command, $extra_opts);
  }

  /**
   * Curl request to webdriver server.
   *
   * @param http_method 'GET', 'POST', or 'DELETE'
   * @param suffix       What to append to the base URL.
   * @param command      The Command object, modelled as a hash.
   * @param extra_opts   key => value pairs of curl options for curl_setopt()
   */
  protected function curl(
    $http_method,
    $suffix,
    $command,
    $extra_opts = array()) {

    $params = $command['parameters'];
    $url = sprintf('%s%s', $this->url, $suffix);

    foreach ($params as $name => $value) {
      if ($name[0] === ':') {
        $url = str_replace($name, $value, $url);
        if ($http_method != 'POST') {
          unset($params[$name]);
        }
      }
    }

    $url = str_replace(':sessionId', $command['sessionId'], $url);

    if ($params && is_array($params) && $http_method !== 'POST') {
      throw new Exception(sprintf(
        'The http method called for %s is %s but it has to be POST' .
        ' if you want to pass the JSON params %s',
        $suffix,
        $http_method,
        json_encode($params)));
    }

    $curl = curl_init($url);

    curl_setopt($curl, CURLOPT_TIMEOUT, 300);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt(
      $curl,
      CURLOPT_HTTPHEADER,
      array(
        'Content-Type: application/json;charset=UTF-8',
        'Accept: application/json'));

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

    $raw_results = trim(curl_exec($curl));
    $info = curl_getinfo($curl);

    if ($error = curl_error($curl)) {
      $msg = sprintf(
        'Curl error thrown for http %s to %s',
        $http_method,
        $url);
      if ($params && is_array($params)) {
        $msg .= sprintf(' with params: %s', json_encode($params));
      }
      throw new WebDriverCurlException($msg . "\n\n" . $error);
    }
    curl_close($curl);

    $results = json_decode($raw_results, true);

    $value = null;
    if (is_array($results) && array_key_exists('value', $results)) {
      $value = $results['value'];
    }

    $message = null;
    if (is_array($results) && array_key_exists('message', $results)) {
      $message = $results['message'];
    }

    $sessionId = null;
    if (is_array($results) && array_key_exists('sessionId', $results)) {
      $sessionId = $results['sessionId'];
    }

    WebDriverException::throwException($results['status'], $message, $results);

    return array('value' => $value, 'info' => $info, 'sessionId' => $sessionId);
  }

}
