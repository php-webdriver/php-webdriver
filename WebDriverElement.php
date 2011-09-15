<?php
// Copyright 2004-present Facebook. All Rights Reserved.

final class WebDriverElement extends WebDriverContainer {
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
      'attribute' => 'GET',
      'equals' => 'GET',
      'location' => 'GET',
      'location_in_view' => 'GET',
      'size' => 'GET',
      'css' => 'GET',
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

  protected function getElementPath($element_id) {
    return preg_replace(sprintf('/%s$/', $this->id), $element_id, $this->url);
  }
}
