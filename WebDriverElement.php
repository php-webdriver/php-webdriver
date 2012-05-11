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

final class WebDriverElement extends WebDriverContainer {
  protected function methods() {
    return array(
      'click' => 'POST',
      'submit' => 'POST',
      'text' => 'GET',
      'value' => 'POST',
      'name' => 'GET',
      'clear' => 'POST',
      'selected' => 'GET',
      'enabled' => 'GET',
      'displayed' => 'GET',
      'location' => 'GET',
      'location_in_view' => 'GET',
      'size' => 'GET',
    );
  }

  private $id;
  public function __construct($url, $id) {
    $this->id = $id;
    parent::__construct($url);
  }

  public function getID() {
    return $this->id;
  }

  /**
   * element method: /session/:sessionId/element/:id/attribute/:name (GET)
   *
   * Get the value of an element's attribute.
   *
   * @param string $attributeName
   *
   * @return string
   */
  public function attribute($attributeName) {
    $result = $this->curl('GET', '/attribute/' . $attributeName);

    return $result['value'];
  }

  /**
   * element method: /session/:sessionId/element/:id/equals/:other (GET)
   *
   * Test if two element IDs refer to the same DOM element.
   *
   * @param string $otherId
   *
   * @return string
   */
  public function equals($otherId) {
    $result = $this->curl('GET', '/equals/' . $otherId);

    return $result['value'];
  }

  /**
   * element method: /session/:sessionId/element/:id/css/:propertyName (GET)
   *
   * Query the value of an element's computed CSS property.
   *
   * @param string $propertyName
   *
   * @return string
   */
  public function css($propertyName) {
    $result = $this->curl('GET', '/css/' . $propertyName);

    return $result['value'];
  }

  protected function getElementPath($element_id) {
    return preg_replace(sprintf('/%s$/', $this->id), $element_id, $this->url);
  }
}
