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

class RemoteWebDriver implements WebDriver {

  protected $executor;
  protected $mouse;
  protected $keyboard;

  public function __construct(
      $url = 'http://localhost:4444/wd/hub',
      $desired_capabilities = array()) {
    $url = preg_replace('#/+$#', '', $url);

    $command = array(
      'url' => $url,
      'name' => 'newSession',
      'parameters' => array('desiredCapabilities' => $desired_capabilities),
    );
    $response = WebDriverCommandExecutor::remoteExecute($command);

    $this->executor = new WebDriverCommandExecutor(
      $url,
      $response['sessionId']
    );
  }

  /**
   * Close the current window.
   *
   * @return WebDriver The current instance.
   */
  public function close() {
    $this->executor->execute('closeCurrentWindow', array());

    return $this;
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
    $raw_element = $this->executor->execute('findElement', $params);

    return $this->newElement($raw_element['ELEMENT']);
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
    $raw_elements = $this->executor->execute('findElements', $params);

    $elements = array();
    foreach ($raw_elements as $raw_element) {
      $elements[] = $this->newElement($raw_element['ELEMENT']);
    }
    return $elements;
  }

  /**
   * Load a new web page in the current browser window.
   *
   * @return WebDriver The current instance.
   */
  public function get($url) {
    $params = array('url' => (string)$url);
    $this->executor->execute('get', $params);

    return $this;
  }

  /**
   * Get a string representing the current URL that the browser is looking at.
   *
   * @return string The current URL.
   */
  public function getCurrentURL() {
    return $this->executor->execute('getCurrentURL');
  }

  /**
   * Get the source of the last loaded page.
   *
   * @return string The current page source.
   */
  public function getPageSource() {
    return $this->executor->execute('getPageSource');
  }

  /**
   * Get the title of the current page.
   *
   * @return string The title of the current page.
   */
  public function getTitle() {
    return $this->executor->execute('getTitle');
  }

  /**
   * Return an opaque handle to this window that uniquely identifies it within
   * this driver instance.
   *
   * @return string The current window handle.
   */
  public function getWindowHandle() {
    return $this->executor->execute('getCurrentWindowHandle', array());
  }

  /**
   * Get all window handles available to the current session.
   *
   * @return array An array of string containing all available window handles.
   */
  public function getWindowHandles() {
    return $this->executor->execute('getWindowHandles', array());
  }

  /**
   * Quits this driver, closing every associated window.
   *
   * @return void
   */
  public function quit() {
    $this->executor->execute('quit');
    $this->executor = null;
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
    $response = $this->executor->execute('executeScript', $params);

    if (is_array($response)) {
      // TODO: Handle this
      throw new Exception(
        "executeScript with collection response is unimplemented"
      );
    } else {
      return $response;
    }
  }

  /**
   * Take a screenshot of the current page.
   *
   * @param $save_as The path of the screenshot to be saved.
   * @return string The screenshot in PNG format.
   */
  public function takeScreenshot($save_as = null) {
    $screenshot = base64_decode(
      $this->executor->execute('takeScreenshot')
    );
    if ($save_as) {
      file_put_contents($save_as, $screenshot);
    }
    return $screenshot;
  }

  /**
   * Construct a new WebDriverWait by the current WebDriver instance.
   * Sample usage:
   *
   *   $driver->wait(20, 1000)->until(
   *     WebDriverExpectedCondition::titleIs('WebDriver Page')
   *   );
   *
   * @return WebDriverWait
   */
  public function wait(
      $timeout_in_second = 30,
      $interval_in_millisecond = 250) {
    return new WebDriverWait(
      $this, $timeout_in_second, $interval_in_millisecond
    );
  }

  /**
   * An abstraction for managing stuff you would do in a browser menu. For
   * example, adding and deleting cookies.
   *
   * @return WebDriverOptions
   */
  public function manage() {
    return new WebDriverOptions($this->executor);
  }

  /**
   * An abstraction allowing the driver to access the browser's history and to
   * navigate to a given URL.
   *
   * @return WebDriverNavigation
   * @see WebDriverNavigation
   */
  public function navigate() {
    return new WebDriverNavigation($this->executor);
  }

  /**
   * Switch to a different window or frame.
   *
   * @return WebDriverTargetLocator
   * @see WebDriverTargetLocator
   */
  public function switchTo() {
    return new WebDriverTargetLocator($this->executor, $this);
  }

  /**
   * @return WebDriverMouse
   */
  public function getMouse() {
    if (!$this->mouse) {
      $this->mouse = new RemoteMouse($this->executor);
    }
    return $this->mouse;
  }

  /**
   * @return WebDriverKeyboard
   */
  public function getKeyboard() {
    throw new UnsupportedOperationException('not yet implemented');
  }

  /**
   * Return the WebDriverElement with the given id.
   *
   * @param string $id The id of the element to be created.
   * @return WebDriverElement
   */
  private function newElement($id) {
    return new RemoteWebElement($this->executor, $id);
  }

}
