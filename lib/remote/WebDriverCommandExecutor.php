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
  private static $commands = array(
    'acceptAlert' =>       array('method' => 'POST', 'url' => '/session/:sessionId/accept_alert'),
    'addCookie' =>         array('method' => 'POST', 'url' => '/session/:sessionId/cookie'),
    'clear' =>             array('method' => 'POST', 'url' => '/session/:sessionId/element/:id/clear'),
    'clickElement' =>      array('method' => 'POST', 'url' => '/session/:sessionId/element/:id/click'),
    'closeCurrentWindow' => array('method' => 'DELETE', 'url' => '/session/:sessionId/window'),
    'deleteAllCookies' =>  array('method' => 'DELETE',  'url' => '/session/:sessionId/cookie'),
    'deleteCookie' =>      array('method' => 'DELETE',  'url' => '/session/:sessionId/cookie/:name'),
    'dismissAlert' =>       array('method' => 'POST',   'url' => '/session/:sessionId/dismiss_alert'),
    'elementFindElement' => array('method' => 'POST', 'url' => '/session/:sessionId/element/:id/element'),
    'elementFindElements' => array('method' => 'POST', 'url' => '/session/:sessionId/element/:id/elements'),
    'executeScript' =>     array('method' => 'POST', 'url' => '/session/:sessionId/execute'),
    'findElement' =>       array('method' => 'POST', 'url' => '/session/:sessionId/element'),
    'findElements' =>      array('method' => 'POST', 'url' => '/session/:sessionId/elements'),
    'focusFrame' =>       array('method' => 'POST',  'url' => '/session/:sessionId/frame'),
    'focusWindow' =>       array('method' => 'POST',  'url' => '/session/:sessionId/window'),
    'get' =>               array('method' => 'POST', 'url' => '/session/:sessionId/url'),
    'getAlertText' =>       array('method' => 'GET', 'url' => '/session/:sessionId/alert_text'),
    'getAllCookies' =>      array('method' => 'GET',  'url' => '/session/:sessionId/cookie'),
    'getCurrentURL' =>     array('method' => 'GET',  'url' => '/session/:sessionId/url'),
    'getCurrentWindowHandle' => array('method' => 'GET',  'url' => '/session/:sessionId/window_handle'),
    'getElementAttribute' => array('method' => 'GET',  'url' => '/session/:sessionId/element/:id/attribute/:name'),
    'getElementCSSValue' => array('method' => 'GET',  'url' => '/session/:sessionId/element/:id/css/:propertyName'),
    'getElementLocation' => array('method' => 'GET',  'url' => '/session/:sessionId/element/:id/location'),
    'getElementLocationOnceScrolledIntoView' => array('method' => 'GET',  'url' => '/session/:sessionId/element/:id/location_in_view'),
    'getElementSize' =>    array('method' => 'GET',  'url' => '/session/:sessionId/element/:id/size'),
    'getElementTagName' => array('method' => 'GET',  'url' => '/session/:sessionId/element/:id/name'),
    'getElementText' =>    array('method' => 'GET',  'url' => '/session/:sessionId/element/:id/text'),
    'getPageSource' =>     array('method' => 'GET',  'url' => '/session/:sessionId/source'),
    'getSession' =>        array('method' => 'GET',  'url' => '/session/:sessionId'),
    'getTitle' =>          array('method' => 'GET',  'url' => '/session/:sessionId/title'),
    'getWindowHandles' => array('method' => 'GET',  'url' => '/session/:sessionId/window_handles'),
    'getWindowPosition' => array('method' => 'GET',  'url' => '/session/:sessionId/window/:windowHandle/position'),
    'getWindowSize' =>     array('method' => 'GET',  'url' => '/session/:sessionId/window/:windowHandle/size'),
    'goBack' =>            array('method' => 'POST',  'url' => '/session/:sessionId/back'),
    'goForward' =>         array('method' => 'POST',  'url' => '/session/:sessionId/forward'),
    'isElementDisplayed'=> array('method' => 'GET',  'url' => '/session/:sessionId/element/:id/displayed'),
    'isElementEnabled'=>   array('method' => 'GET',  'url' => '/session/:sessionId/element/:id/enabled'),
    'isElementSelected'=>  array('method' => 'GET',  'url' => '/session/:sessionId/element/:id/selected'),
    'maximizeWindow' =>    array('method' => 'POST', 'url' => '/session/:sessionId/window/:windowHandle/maximize'),
    'mouseButtonDown' =>   array('method' => 'POST', 'url' => '/session/:sessionId/buttondown'),
    'mouseButtonUp' =>     array('method' => 'POST', 'url' => '/session/:sessionId/buttonup'),
    'mouseClick' =>        array('method' => 'POST', 'url' => '/session/:sessionId/click'),
    'mouseDoubleClick' =>  array('method' => 'POST', 'url' => '/session/:sessionId/doubleclick'),
    'mouseMoveTo' =>       array('method' => 'POST', 'url' => '/session/:sessionId/moveto'),
    'newSession' =>        array('method' => 'POST', 'url' => '/session'),
    'quit' =>              array('method' => 'DELETE', 'url' => '/session/:sessionId'),
    'refreshPage' =>       array('method' => 'POST', 'url' => '/session/:sessionId/refresh'),
    'sendFile' =>          array('method' => 'POST', 'url' => '/session/:sessionId/file'), // undocumented
    'sendKeys' =>          array('method' => 'POST', 'url' => '/session/:sessionId/keys'),
    'sendKeysToAlert' =>    array('method' => 'POST', 'url' => '/session/:sessionId/alert_text'),
    'sendKeysToElement' => array('method' => 'POST', 'url' => '/session/:sessionId/element/:id/value'),
    'setImplicitWaitTimeout' => array('method' => 'POST', 'url' => '/session/:sessionId/timeouts/implicit_wait'),
    'setPageLoadTimeout' => array('method' => 'POST', 'url' => '/session/:sessionId/timeouts'),
    'setScriptTimeout' =>  array('method' => 'POST', 'url' => '/session/:sessionId/timeouts/async_script'),
    'setWindowPosition' => array('method' => 'POST', 'url' => '/session/:sessionId/window/:windowHandle/position'),
    'setWindowSize' =>     array('method' => 'POST', 'url' => '/session/:sessionId/window/:windowHandle/size'),
    'submitElement' =>     array('method' => 'POST', 'url' => '/session/:sessionId/element/:id/submit'),
    'takeScreenshot' =>    array('method' => 'GET',  'url' => '/session/:sessionId/screenshot'),
  );

  protected $url;
  protected $sessionID;
  protected $capabilities;

  public function __construct($url, $session_id) {
    $this->url = $url;
    $this->sessionID = $session_id;
    $this->capabilities = $this->execute('getSession', array());
  }

  public function execute($name, array $params = array()) {
    $command = array(
      'url' => $this->url,
      'sessionId' => $this->sessionID,
      'name' => $name,
      'parameters' => $params,
    );
    $raw = self::remoteExecute($command);
    return $raw['value'];
  }

  /**
   * Execute a command on a remote server. The command should be an array
   * contains
   *   url        : the url of the remote server
   *   sessionId  : the session id if needed
   *   name       : the name of the command
   *   parameters : the parameters of the command required
   *
   * @return array The response of the command.
   */
  public static function remoteExecute($command) {
    if (!isset(self::$commands[$command['name']])) {
      throw new Exception($command['name']." is not a valid command.");
    }
    $raw = self::$commands[$command['name']];
    $extra_opts = array();

    if ($command['name'] == 'newSession') {
      $extra_opts[CURLOPT_FOLLOWLOCATION] = true;
    }

    return self::curl(
      $raw['method'],
      sprintf("%s%s", $command['url'], $raw['url']),
      $command,
      $extra_opts
    );
  }

  /**
   * Curl request to webdriver server.
   *
   * @param http_method 'GET', 'POST', or 'DELETE'
   * @param suffix       What to append to the base URL.
   * @param command      The Command object, modelled as a hash.
   * @param extra_opts   key => value pairs of curl options for curl_setopt()
   */
  protected static function curl(
    $http_method,
    $url,
    $command,
    $extra_opts = array()) {

    $params = $command['parameters'];

    foreach ($params as $name => $value) {
      if ($name[0] === ':') {
        $url = str_replace($name, $value, $url);
        if ($http_method != 'POST') {
          unset($params[$name]);
        }
      }
    }

    if (isset($command['sessionId'])) {
      $url = str_replace(':sessionId', $command['sessionId'], $url);
    }

    if ($params && is_array($params) && $http_method !== 'POST') {
      throw new Exception(sprintf(
        'The http method called for %s is %s but it has to be POST' .
        ' if you want to pass the JSON params %s',
        $url,
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

    $status = isset($results['status']) ? $results['status'] : 0;
    WebDriverException::throwException($status, $message, $results);

    return array('value' => $value, 'info' => $info, 'sessionId' => $sessionId);
  }

  public function getSessionID() {
    return $this->sessionID;
  }

}
