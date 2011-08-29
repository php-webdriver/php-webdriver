<?php
// Copyright 2004-present Facebook. All Rights Reserved.

class WebDriverSimpleItem extends WebDriver {
  private $_methods = array();
  protected function methods() {
    return $this->_methods;
  }

  public function setMethods($methods) {
    $this->_methods = $methods;
    return $this;
  }
}
