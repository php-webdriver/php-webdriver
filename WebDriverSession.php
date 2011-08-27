<?php
// Copyright 2004-present Facebook. All Rights Reserved.

class WebDriverSession extends WebDriver {
  protected function methods() {
    return array(
      'url' => array('GET', 'POST'),
    );
  }

  public function open($url) {
    $this->postUrl(array('url' => $url));
    return $this;
  }

  public function element($using, $value) {
    $results = $this->curl(
      'element', 
      array(
        'using' => $using,
        'value' => $value),
      'POST');

    return $this->webDriverElement($results['value']);
  }

  public function elements($using, $value) {
    $results = $this->curl(
      'elements',
      array(
        'using' => $using,
        'value' => $value
      ),
      'POST');

    return array_filter(array_map(
      array($this, 'webDriverElement'), $results['value']));
  }

  private function webDriverElement($value) {
    if (array_key_exists('ELEMENT', $value)) {
      return new WebDriverElement(sprintf(
        '%s/element/%s',
        $this->url,
        $value['ELEMENT']));
    } else {
      return null;
    }
  }
}
