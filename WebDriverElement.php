<?php
// Copyright 2004-present Facebook. All Rights Reserved.

class WebDriverElement extends WebDriverSession {
  protected function methods() {
    return array(
      'text' => 'GET',
    );
  }
}