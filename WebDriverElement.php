<?php
// Copyright 2004-present Facebook. All Rights Reserved.

class WebDriverElement extends WebDriverContainer {
  protected function methods() {
    return array(
      'active' => 'POST',
      'click' => 'POST',
      'submit' => 'POST',
      'text' => 'GET',
      'value' => 'POST',
      'name' => 'GET',
      'clear' => 'POST',
      'selected' => 'GET',
      'enabled' => 'GET',
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

  // /session/:sessionId/element/:id/attribute/:name (GET)
  public function attribute($name) {
    return $this->curl('GET', '/attribute/' . $name);
  }

  // /session/:sessionId/element/:id/equals/:other (GET)
  public function equals($other_element) {
    return $this->curl('GET', '/equals/' . $other_element->getID());
  }

  // /session/:sessionId/element/:id/css/:propertyName (GET)
  public function css($property_name) {
    return $this->curl('GET', '/css/' . $property_name);
  }

  protected function getElementPath($element_id) {
    return preg_replace(sprintf('/%s$/', $this->id), $element_id, $this->url);
  }
}