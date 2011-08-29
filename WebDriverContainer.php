<?php
// Copyright 2004-present Facebook. All Rights Reserved.

abstract class WebDriverContainer extends WebDriverBase {
  public function element($using, $value) {
    $results = $this->curl(
      'POST',
      '/element', 
      array(
        'using' => $using,
        'value' => $value));

    return $this->webDriverElement($results['value']);
  }

  public function elements($using, $value) {
    $results = $this->curl(
      'POST',
      '/elements',
      array(
        'using' => $using,
        'value' => $value
      ));

    return array_filter(array_map(
      array($this, 'webDriverElement'), $results['value']));
  }

  private function webDriverElement($value) {
    return (array_key_exists('ELEMENT', $value)) ?
      new WebDriverElement($this->getElementPath($value['ELEMENT']),
                           $value['ELEMENT']) :
      null;
  }
  abstract protected function getElementPath($element_id);
}
