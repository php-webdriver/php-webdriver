<?php
// Copyright 2004-present Facebook. All Rights Reserved.

class WebDriver extends WebDriverBase {
  protected function methods() {
    return array(
      'status' => 'GET',
    );
  }

  public function session($browser = 'firefox') {
    $results = $this->curl(
      'POST',
      '/session',
      array('desiredCapabilities' => array('browserName' => $browser)),
      array(CURLOPT_HEADER => true,
            CURLOPT_FOLLOWLOCATION => true));

    return new WebDriverSession($results['info']['url']);
  }
}