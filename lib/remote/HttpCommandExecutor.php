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

/**
 * Command executor talking to the standalone server via HTTP.
 */
class HttpCommandExecutor implements WebDriverCommandExecutor {

  /**
   * @see
   *   http://code.google.com/p/selenium/wiki/JsonWireProtocol#Command_Reference
   */
  private static $commands = array(
    DriverCommand::ACCEPT_ALERT =>            array('method' => 'POST', 'url' => '/session/:sessionId/accept_alert'),
    DriverCommand::ADD_COOKIE =>              array('method' => 'POST', 'url' => '/session/:sessionId/cookie'),
    DriverCommand::CLEAR_ELEMENT =>           array('method' => 'POST', 'url' => '/session/:sessionId/element/:id/clear'),
    DriverCommand::CLICK_ELEMENT =>           array('method' => 'POST', 'url' => '/session/:sessionId/element/:id/click'),
    DriverCommand::CLOSE =>                   array('method' => 'DELETE', 'url' => '/session/:sessionId/window'),
    DriverCommand::DELETE_ALL_COOKIES =>      array('method' => 'DELETE',  'url' => '/session/:sessionId/cookie'),
    DriverCommand::DELETE_COOKIE =>           array('method' => 'DELETE',  'url' => '/session/:sessionId/cookie/:name'),
    DriverCommand::DISMISS_ALERT =>           array('method' => 'POST',   'url' => '/session/:sessionId/dismiss_alert'),
    DriverCommand::ELEMENT_EQUALS =>          array('method' => 'GET', 'url' => '/session/:sessionId/element/:id/equals/:other'),
    DriverCommand::FIND_CHILD_ELEMENT =>      array('method' => 'POST', 'url' => '/session/:sessionId/element/:id/element'),
    DriverCommand::FIND_CHILD_ELEMENTS =>     array('method' => 'POST', 'url' => '/session/:sessionId/element/:id/elements'),
    DriverCommand::EXECUTE_SCRIPT =>          array('method' => 'POST', 'url' => '/session/:sessionId/execute'),
    DriverCommand::EXECUTE_ASYNC_SCRIPT =>    array('method' => 'POST', 'url' => '/session/:sessionId/execute_async'),
    DriverCommand::FIND_ELEMENT =>            array('method' => 'POST', 'url' => '/session/:sessionId/element'),
    DriverCommand::FIND_ELEMENTS =>           array('method' => 'POST', 'url' => '/session/:sessionId/elements'),
    DriverCommand::SWITCH_TO_FRAME =>         array('method' => 'POST',  'url' => '/session/:sessionId/frame'),
    DriverCommand::SWITCH_TO_WINDOW =>        array('method' => 'POST',  'url' => '/session/:sessionId/window'),
    DriverCommand::GET =>                     array('method' => 'POST', 'url' => '/session/:sessionId/url'),
    DriverCommand::GET_ACTIVE_ELEMENT =>      array('method' => 'POST', 'url' => '/session/:sessionId/element/active'),
    DriverCommand::GET_ALERT_TEXT =>          array('method' => 'GET', 'url' => '/session/:sessionId/alert_text'),
    DriverCommand::GET_ALL_COOKIES =>         array('method' => 'GET',  'url' => '/session/:sessionId/cookie'),
    DriverCommand::GET_AVAILABLE_LOG_TYPES => array('method' => 'GET', 'url' => '/session/:sessionId/log/types'),
    DriverCommand::GET_CURRENT_URL =>         array('method' => 'GET',  'url' => '/session/:sessionId/url'),
    DriverCommand::GET_CURRENT_WINDOW_HANDLE => array('method' => 'GET',  'url' => '/session/:sessionId/window_handle'),
    DriverCommand::GET_ELEMENT_ATTRIBUTE =>   array('method' => 'GET',  'url' => '/session/:sessionId/element/:id/attribute/:name'),
    DriverCommand::GET_ELEMENT_VALUE_OF_CSS_PROPERTY =>     array('method' => 'GET',  'url' => '/session/:sessionId/element/:id/css/:propertyName'),
    DriverCommand::GET_ELEMENT_LOCATION =>    array('method' => 'GET',  'url' => '/session/:sessionId/element/:id/location'),
    DriverCommand::GET_ELEMENT_LOCATION_ONCE_SCROLLED_INTO_VIEW => array('method' => 'GET',  'url' => '/session/:sessionId/element/:id/location_in_view'),
    DriverCommand::GET_ELEMENT_SIZE =>        array('method' => 'GET',  'url' => '/session/:sessionId/element/:id/size'),
    DriverCommand::GET_ELEMENT_TAG_NAME =>    array('method' => 'GET',  'url' => '/session/:sessionId/element/:id/name'),
    DriverCommand::GET_ELEMENT_TEXT =>        array('method' => 'GET',  'url' => '/session/:sessionId/element/:id/text'),
    DriverCommand::GET_LOG =>                 array('method' => 'POST', 'url' => '/session/:sessionId/log'),
    DriverCommand::GET_PAGE_SOURCE =>         array('method' => 'GET',  'url' => '/session/:sessionId/source'),
    DriverCommand::GET_SCREEN_ORIENTATION =>  array('method' => 'GET',  'url' => '/session/:sessionId/orientation'),
    DriverCommand::GET_CAPABILITIES =>        array('method' => 'GET',  'url' => '/session/:sessionId'),
    DriverCommand::GET_TITLE =>               array('method' => 'GET',  'url' => '/session/:sessionId/title'),
    DriverCommand::GET_WINDOW_HANDLES =>      array('method' => 'GET',  'url' => '/session/:sessionId/window_handles'),
    DriverCommand::GET_WINDOW_POSITION =>     array('method' => 'GET',  'url' => '/session/:sessionId/window/:windowHandle/position'),
    DriverCommand::GET_WINDOW_SIZE =>         array('method' => 'GET',  'url' => '/session/:sessionId/window/:windowHandle/size'),
    DriverCommand::GO_BACK =>                 array('method' => 'POST',  'url' => '/session/:sessionId/back'),
    DriverCommand::GO_FORWARD =>              array('method' => 'POST',  'url' => '/session/:sessionId/forward'),
    DriverCommand::IS_ELEMENT_DISPLAYED=>     array('method' => 'GET',  'url' => '/session/:sessionId/element/:id/displayed'),
    DriverCommand::IS_ELEMENT_ENABLED=>       array('method' => 'GET',  'url' => '/session/:sessionId/element/:id/enabled'),
    DriverCommand::IS_ELEMENT_SELECTED=>      array('method' => 'GET',  'url' => '/session/:sessionId/element/:id/selected'),
    DriverCommand::MAXIMIZE_WINDOW =>         array('method' => 'POST', 'url' => '/session/:sessionId/window/:windowHandle/maximize'),
    DriverCommand::MOUSE_DOWN =>              array('method' => 'POST', 'url' => '/session/:sessionId/buttondown'),
    DriverCommand::MOUSE_UP =>                array('method' => 'POST', 'url' => '/session/:sessionId/buttonup'),
    DriverCommand::CLICK =>                   array('method' => 'POST', 'url' => '/session/:sessionId/click'),
    DriverCommand::DOUBLE_CLICK =>            array('method' => 'POST', 'url' => '/session/:sessionId/doubleclick'),
    DriverCommand::MOVE_TO =>                 array('method' => 'POST', 'url' => '/session/:sessionId/moveto'),
    DriverCommand::NEW_SESSION =>             array('method' => 'POST', 'url' => '/session'),
    DriverCommand::QUIT =>                    array('method' => 'DELETE', 'url' => '/session/:sessionId'),
    DriverCommand::REFRESH =>                 array('method' => 'POST', 'url' => '/session/:sessionId/refresh'),
    DriverCommand::UPLOAD_FILE =>             array('method' => 'POST', 'url' => '/session/:sessionId/file'), // undocumented
    DriverCommand::SEND_KEYS_TO_ACTIVE_ELEMENT => array('method' => 'POST', 'url' => '/session/:sessionId/keys'),
    DriverCommand::SET_ALERT_VALUE =>         array('method' => 'POST', 'url' => '/session/:sessionId/alert_text'),
    DriverCommand::SEND_KEYS_TO_ELEMENT =>    array('method' => 'POST', 'url' => '/session/:sessionId/element/:id/value'),
    DriverCommand::IMPLICITLY_WAIT =>         array('method' => 'POST', 'url' => '/session/:sessionId/timeouts/implicit_wait'),
    DriverCommand::SET_SCREEN_ORIENTATION =>  array('method' => 'POST', 'url' => '/session/:sessionId/orientation'),
    DriverCommand::SET_TIMEOUT =>             array('method' => 'POST', 'url' => '/session/:sessionId/timeouts'),
    DriverCommand::SET_SCRIPT_TIMEOUT =>      array('method' => 'POST', 'url' => '/session/:sessionId/timeouts/async_script'),
    DriverCommand::SET_WINDOW_POSITION =>     array('method' => 'POST', 'url' => '/session/:sessionId/window/:windowHandle/position'),
    DriverCommand::SET_WINDOW_SIZE =>         array('method' => 'POST', 'url' => '/session/:sessionId/window/:windowHandle/size'),
    DriverCommand::SUBMIT_ELEMENT =>          array('method' => 'POST', 'url' => '/session/:sessionId/element/:id/submit'),
    DriverCommand::SCREENSHOT =>              array('method' => 'GET',  'url' => '/session/:sessionId/screenshot'),
    DriverCommand::TOUCH_SINGLE_TAP =>        array('method' => 'POST', 'url' => '/session/:sessionId/touch/click'),
    DriverCommand::TOUCH_DOWN =>              array('method' => 'POST', 'url' => '/session/:sessionId/touch/down'),
    DriverCommand::TOUCH_DOUBLE_TAP =>        array('method' => 'POST', 'url' => '/session/:sessionId/touch/doubleclick'),
    DriverCommand::TOUCH_FLICK =>             array('method' => 'POST', 'url' => '/session/:sessionId/touch/flick'),
    DriverCommand::TOUCH_LONG_PRESS =>        array('method' => 'POST', 'url' => '/session/:sessionId/touch/longclick'),
    DriverCommand::TOUCH_MOVE =>              array('method' => 'POST', 'url' => '/session/:sessionId/touch/move'),
    DriverCommand::TOUCH_SCROLL =>            array('method' => 'POST', 'url' => '/session/:sessionId/touch/scroll'),
    DriverCommand::TOUCH_UP =>                array('method' => 'POST', 'url' => '/session/:sessionId/touch/up'),
  );

  /**
   * @var string
   */
  protected $url;

  /**
   * @param string $url
   * @param string $session_id
   */
  public function __construct($url) {
    $this->url = $url;
  }

  /**
   * @param WebDriverCommand $command
   *
   * @return mixed
   */
  public function execute(WebDriverCommand $command) {
    $response = self::remoteExecute($command, $this->url);
    return $response;
  }

  /**
   * This method is deprecated.
   * Execute a command on a remote server.
   *
   * @param WebDriverCommand
   * @param array $curl_opts An array of curl options.
   *
   * @return array The response of the command.
   * @throws Exception
   */
  public static function remoteExecute(
    WebDriverCommand $command,
    string $url,
    array $curl_opts = array()
  ) {
    if (!isset(self::$commands[$command->getName()])) {
      throw new Exception($command->getName()." is not a valid command.");
    }
    $raw = self::$commands[$command->getName()];

    if ($command->getName() == DriverCommand::NEW_SESSION) {
      $curl_opts[CURLOPT_FOLLOWLOCATION] = true;
    }

    return self::curl(
      $raw['method'],
      sprintf("%s%s", $url, $raw['url']),
      $command,
      $curl_opts
    );
  }

  /**
   * Curl request to webdriver server.
   *
   * @param string $http_method 'GET', 'POST', or 'DELETE'
   * @param string $url
   * @param array $command      The Command object, modelled as a hash.
   * @param array $extra_opts   key => value pairs of curl options for
   *                            curl_setopt()
   * @return array
   * @throws Exception
   */
  protected static function curl(
    $http_method,
    $url,
    WebDriverCommand $command,
    array $extra_opts = array()) {
    $params = $command->getParameters();

    foreach ($params as $name => $value) {
      if ($name[0] === ':') {
        $url = str_replace($name, $value, $url);
        if ($http_method != 'POST') {
          unset($params[$name]);
        }
      }
    }

    if ($command->getSessionID()) {
      $url = str_replace(':sessionId', $command->getSessionID(), $url);
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

    if ($error = curl_error($curl)) {
      $msg = sprintf(
        'Curl error thrown for http %s to %s',
        $http_method,
        $url);
      if ($params && is_array($params)) {
        $msg .= sprintf(' with params: %s', json_encode($params));
      }
      WebDriverException::throwException(-1, $msg . "\n\n" . $error, array());
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

    $sessionId = null;
    if (is_array($results) && array_key_exists('sessionId', $results)) {
      $sessionId = $results['sessionId'];
    }

    $status = isset($results['status']) ? $results['status'] : 0;
    WebDriverException::throwException($status, $message, $results);

    $response = new WebDriverResponse($sessionId);
    return $response
      ->setStatus($status)
      ->setValue($value);
  }

  /**
   * @return string
   */
  public function getAddressOfRemoteServer() {
    return $this->url;
  }
}
