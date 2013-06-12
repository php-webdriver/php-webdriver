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

class WebDriver {

  protected $executor;
  protected $sessionID;
  protected $capabilities;

  public function __construct(
      $url = 'http://localhost:4444/wd/hub',
      $desired_capabilities = array()) {

    $this->executor = new WebDriverCommandExecutor($url);

    $params = array(
      'desiredCapabilities' => $desired_capabilities,
    );

    $response = $this->execute('newSession', $params);
    $this->capabilities = $response['value'];
    $this->sessionID = $response['sessionId'];
  }

  public function close() {
    throw new Exception("close() is unimplemented");
  }

  /**
   * Find the first WebDriverElement using the given mechanism.
   *
   * @param WebDriverBy $by
   * @return WebDriverElement NoSuchElementWebDriverError is thrown in
   *    WebDriverCommandExecutor if no element is found.
   * @see WebDriverBy
   */
  public function findElement(WebDriverBy $by) {
    $params = array('using' => $by->getMechanism(), 'value' => $by->getValue());
    $raw_element = $this->execute('findElement', $params);

    return $this->newElement($raw_element['value']['ELEMENT']);
  }

  /**
   * Find all WebDriverElements within the current page using the given
   * mechanism.
   *
   * @param WebDriverBy $by
   * @return array A list of all WebDriverElements, or an empty array if
   *    nothing matches
   * @see WebDriverBy
   */
  public function findElements(WebDriverBy $by) {
    $params = array('using' => $by->getMechanism(), 'value' => $by->getValue());
    $raw_elements = $this->execute('findElements', $params);

    $elements = array();
    foreach ($raw_elements['value'] as $raw_element) {
      $elements[] = $this->newElement($raw_element['ELEMENT']);
    }
    return $elements;
  }

  /**
   * Load a new web page in the current browser window.
   *
   * @return void
   */
  public function get($url) {
    $params = array('url' => (string)$url);
    $this->execute('get', $params);
  }

  /**
   * Get a string representing the current URL that the browser is looking at.
   *
   * @return string The current URL.
   */
  public function getCurrentURL() {
    $raw = $this->execute('getCurrentURL');
    return $raw['value'];
  }

  /**
   * Get the source of the last loaded page.
   *
   * @return string The current page source.
   */
  public function getPageSource() {
    $raw = $this->execute('getPageSource');
    return $raw['value'];
  }

  /**
   * Get the title of the current page.
   *
   * @return string The title of the current page.
   */
  public function getTitle() {
    $raw = $this->execute('getTitle');
    return $raw['value'];
  }

  /**
   * Return an opaque handle to this window that uniquely identifies it within
   * this driver instance.
   */
  public function getWindowHandle() {
    throw new Exception("getWindowHandle is unimplemented");
  }

  /**
   * Return a set of window handles.
   */
  public function getWindowHandles() {
    throw new Exception("getWindowHandles is unimplemented");
  }

  /**
   * Quits this driver, closing every associated window.
   *
   * @return void
   */
  public function quit() {
    $this->execute('quit');
    $this->sessionID = null;
  }

  /**
   * Inject a snippet of JavaScript into the page for execution in the context
   * of the currently selected frame. The executed script is assumed to be
   * synchronous and the result of evaluating the script will be returned.
   *
   * @param string $script The script to inject.
   * @param array $arguments The arguments of the script.
   * @return mixed The return value of the script.
   */
  public function executeScript($script, array $arguments = array()) {
    $script = str_replace('"', '\"', $script);
    $args = array();
    foreach ($arguments as $arg) {
      if ($arg instanceof WebDriverElement) {
        array_push($args, array('ELEMENT' => $arg->getID()));
      } else {
        // TODO: Handle the case where arg is a collection
        if (is_array($arg)) {
          throw new Exception(
            "executeScript with collection paramatar is unimplemented"
          );
        }
        array_push($args, $arg);
      }
    }

    $params = array('script' => $script, 'args' => $args);
    $response = $this->execute('executeScript', $params);

    if (is_array($response['value'])) {
      // TODO: Handle this
      throw new Exception(
        "executeScript with collection response is unimplemented"
      );
    } else {
      return $response['value'];
    }
  }

  /**
   * An abstraction for managing stuff you would do in a browser menu. For
   * example, adding and deleting cookies.
   *
   * @return WebDriverOptions
   */
  public function manage() {
    return new WebDriverOptions(
      $this->executor,
      $this->sessionID
    );
  }

  /**
   * An abstraction allowing the driver to access the browser's history and to
   * navigate to a given URL.
   *
   * @return WebDriverNavigation
   * @see WebDriverNavigation
   */
  public function navigate() {
    return new WebDriverNavigation(
      $this->executor,
      $this->sessionID
    );
  }

  /**
   * Execute command.
   *
   * @param string $name The name of the command.
   * @return mixed
   */
  protected function execute($name, array $params = array()) {
    $command = array(
      'sessionId' => $this->sessionID,
      'name' => $name,
      'parameters' => $params,
    );
    return $this->executor->execute($command);
  }

  /**
   * Return the WebDriverElement with the given id.
   *
   * @param string $id The id of the element to be created.
   * @return WebDriverElement
   */
  private function newElement($id) {
    return new WebDriverElement($this->executor, $this->sessionID, $id);
  }

}
