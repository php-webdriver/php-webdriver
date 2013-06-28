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
 * Represents an HTML element.
 */
class WebDriverElement {

  protected $executor;
  protected $sessionID;
  protected $id;

  public function __construct($executor, $session_id, $id) {
    $this->executor = $executor;
    $this->sessionID = $session_id;
    $this->id = $id;
  }

  /**
   * If this element is a TEXTAREA or text INPUT element, this will clear the
   * value.
   *
   * @return WebDriverElement The current instance.
   */
  public function clear() {
    $this->execute('clear');
    return $this;
  }

  /**
   * Click this element.
   *
   * @return WebDriverElement The current instance.
   */
  public function click() {
    $this->execute('clickElement');
    return $this;
  }

  /**
   * Find the first WebDriverElement within this element using the given
   * mechanism.
   *
   * @param WebDriverBy $by
   * @return WebDriverElement NoSuchElementWebDriverError is thrown in
   *    WebDriverCommandExecutor if no element is found.
   * @see WebDriverBy
   */
  public function findElement(WebDriverBy $by) {
    $params = array('using' => $by->getMechanism(), 'value' => $by->getValue());
    $raw_element = $this->execute('elementFindElement', $params);

    return $this->newElement($raw_element['ELEMENT']);
  }

  /**
   * Find all WebDriverElements within this element using the given mechanism.
   *
   * @param WebDriverBy $by
   * @return array A list of all WebDriverElements, or an empty array if
   *    nothing matches
   * @see WebDriverBy
   */
  public function findElements(WebDriverBy $by) {
    $params = array('using' => $by->getMechanism(), 'value' => $by->getValue());
    $raw_elements = $this->execute('elementFindElements', $params);

    $elements = array();
    foreach ($raw_elements as $raw_element) {
      $elements[] = $this->newElement($raw_element['ELEMENT']);
    }
    return $elements;
  }

  /**
   * Get the value of a the given attribute of the element.
   *
   * @param string $attribute_name The name of the attribute.
   * @return string The value of the attribute.
   */
  public function getAttribute($attribute_name) {
    $params = array(':name' => $attribute_name);
    return $this->execute('getElementAttribute', $params);
  }

  /**
   * Get the value of a given CSS property.
   *
   * @param string $css_property_name The name of the CSS property.
   * @return string The value of the CSS property.
   */
  public function getCSSValue($css_property_name) {
    $params = array(':propertyName' => $css_property_name);
    return $this->execute('getElementCSSValue', $params);
  }

  /**
   * Get the location of element relative to the top-left corner of the page.
   *
   * @return WebDriverLocation The location of the element.
   */
  public function getLocation() {
    $location = $this->execute('getElementLocation');
    return new WebDriverPoint($location['x'], $location['y']);
  }

  /**
   * Get the size of element.
   *
   * @return WebDriverDimension The dimension of the element.
   */
  public function getSize() {
    $size = $this->execute('getElementSize');
    return new WebDriverDimension($size['width'], $size['height']);
  }

  /**
   * Get the tag name of this element.
   *
   * @return string The tag name.
   */
  public function getTagName() {
    return $this->execute('getElementTagName');
  }

  /**
   * Get the visible (i.e. not hidden by CSS) innerText of this element,
   * including sub-elements, without any leading or trailing whitespace.
   *
   * @return string The visible innerText of this element.
   */
  public function getText() {
    return $this->execute('getElementText');
  }

  /**
   * Is this element displayed or not? This method avoids the problem of having
   * to parse an element's "style" attribute.
   *
   * @return bool
   */
  public function isDisplayed() {
    return $this->execute('isElementDisplayed');
  }

  /**
   * Is the element currently enabled or not? This will generally return true
   * for everything but disabled input elements.
   *
   * @return bool
   */
  public function isEnabled() {
    return $this->execute('isElementEnabled');
  }

  /**
   * Determine whether or not this element is selected or not.
   *
   * @return bool
   */
  public function isSelected() {
    return $this->execute('isElementSelected');
  }

  /**
   * Simulate typing into an element, which may set its value.
   *
   * @param mixed $value The data to be typed.
   * @return WebDriverElement The current instance.
   */
  public function sendKeys($value) {
    $params = array('value' => array((string)$value));
    $this->execute('sendKeysToElement', $params);
    return $this;
  }

  /**
   * If this current element is a form, or an element within a form, then this
   * will be submitted to the remote server.
   *
   * @return WebDriverElement The current instance.
   */
  public function submit() {
    $this->execute('submitElement');
    return $this;
  }

  public function getID() {
    return $this->id;
  }

  private function execute($name, array $params = array()) {
    $params[':id'] = $this->id;
    $command = array(
      'sessionId' => $this->sessionID,
      'name' => $name,
      'parameters' => $params,
    );
    $raw = $this->executor->execute($command);
    return $raw['value'];
  }

  /**
   * Return the WebDriverElement with $id
   *
   * @return WebDriverElement
   */
  private function newElement($id) {
    return new WebDriverElement($this->executor, $this->sessionID, $id);
  }
}
