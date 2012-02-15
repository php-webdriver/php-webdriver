<?php
// Copyright 2011-present Element 34
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

include_once('WebDriverExceptions.php');

class PHPWebDriver_ProxyType {
  public static $DIRECT = array('ff_value' => 0, 'string' => 'direct');           // Direct connection, no proxy (default on Windows).
  public static $MANUAL = array('ff_value' => 1, 'string' => 'manual');           // Manual proxy settings (e.g., for httpProxy).
  public static $PAC = array('ff_value' => 2, 'string' => 'pac');                 // Proxy autoconfiguration from URL.
  public static $RESERVED_1 = array('ff_value' => 3, 'string' => 'reserved1');    // Never used.
  public static $AUTODETECT = array('ff_value' => 4, 'string' => 'autodetect');   // Proxy autodetection (presumably with WPAD).
  public static $SYSTEM = array('ff_value' => 5, 'string' => 'system');           // Use system settings (default on Linux).
  public static $UNSPECIFIED = array('ff_value' => 6, 'string' => 'unspecified');
}

class PHPWebDriver_WebDriverProxy {
  function __set($property, $value) {
    switch($property) {
        case "autodetect":
          if (is_bool($value)) {
            if ($this->$property == $value) {
              break;
            }
            $this->proxyType = PHPWebDriver_ProxyType::$AUTODETECT;
            $this->autodetect = $value;
          } else {
            throw new InvalidArgumentException('Value needs to be boolean');
          }
          break;
        case "ftpProxy":
        case "httpProxy":
        case "noProxy":
          $this->proxyType = PHPWebDriver_ProxyType::$MANUAL;
          $this->$property = $value;
          break;
        case "proxyAutoconfigUrl":
          $this->proxyType = PHPWebDriver_ProxyType::$PAC;
          $this->$property = $value;
          break;
        case "sslProxy":
          $this->proxyType = $value;
          $this->$property = $value;
          break;
        default:
          $this->$property = $value;
    }
  }
  
  public function add_to_capabilities(&$capabilities) {
    $proxy = array("proxyType" => $this->proxyType["string"]);
    if (isset($this->autodetect) && $this->autodetect) {
      $proxy["autodetect"] = $this->autodetect;
    }
    if (isset($this->ftpProxy)) {
      $proxy["ftpProxy"] = $this->ftpProxy;
    }
    if (isset($this->httpProxy)) {
      $proxy["httpProxy"] = $this->httpProxy;
    }
    if (isset($this->proxyAutoconfigUrl)) {
      $proxy["proxyAutoconfigUrl"] = $this->proxyAutoconfigUrl;
    }
    if (isset($this->sslProxy)) {
      $proxy["sslProxy"] = $this->sslProxy;
    }
    $capabilities["proxy"] = $proxy;
  }
}
?>