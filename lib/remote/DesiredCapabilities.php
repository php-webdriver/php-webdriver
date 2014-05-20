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

class DesiredCapabilities implements WebDriverCapabilities {

  private $capabilities;

  public function __construct(array $capabilities = array()) {
    $this->capabilities = $capabilities;
  }

  /**
   * @return string The name of the browser.
   */
  public function getBrowserName() {
    return $this->get(WebDriverCapabilityType::BROWSER_NAME, '');
  }

  public function setBrowserName($browser_name) {
    $this->set(WebDriverCapabilityType::BROWSER_NAME, $browser_name);
    return $this;
  }

  /**
   * @return string The version of the browser.
   */
  public function getVersion() {
    return $this->get(WebDriverCapabilityType::VERSION, '');
  }

  public function setVersion($version) {
    $this->set(WebDriverCapabilityType::VERSION, $version);
    return $this;
  }

  /**
   * @return mixed The value of a capability.
   */
  public function getCapability($name) {
    $this->get($name);
  }

  public function setCapability($name, $value) {
    $this->set($name, $value);
    return $this;
  }

  /**
   * @return string The name of the platform.
   */
  public function getPlatform() {
    return $this->get(WebDriverCapabilityType::PLATFORM, '');
  }

  public function setPlatform($platform) {
    $this->set(WebDriverCapabilityType::PLATFORM, $platform);
    return $this;
  }


  /**
   * @return bool Whether the value is not null and not false.
   */
  public function is($capability_name) {
    return (bool)$this->get($capability_name);
  }

  /**
   * @return bool Whether javascript is enabled.
   */
  public function isJavascriptEnabled() {
    return $this->get(WebDriverCapabilityType::JAVASCRIPT_ENABLED, false);
  }

  public function setJavascriptEnabled($enabled) {
    $this->set(WebDriverCapabilityType::JAVASCRIPT_ENABLED, $enabled);
    return $this;
  }

  public function toArray() {
    return $this->capabilities;
  }

  private function set($key, $value) {
    $this->capabilities[$key] = $value;
    return $this;
  }

  private function get($key, $default = null) {
    return isset($this->capabilities[$key])
           ? $this->capabilities[$key]
           : $default;
  }

  public static function android() {
    return new DesiredCapabilities(array(
      WebDriverCapabilityType::BROWSER_NAME => WebDriverBrowserType::ANDROID,
      WebDriverCapabilityType::PLATFORM => WebDriverPlatform::ANDROID,
    ));
  }

  public static function chrome() {
    return new DesiredCapabilities(array(
      WebDriverCapabilityType::BROWSER_NAME => WebDriverBrowserType::CHROME,
      WebDriverCapabilityType::PLATFORM => WebDriverPlatform::ANY,
    ));
  }

  public static function firefox() {
    return new DesiredCapabilities(array(
      WebDriverCapabilityType::BROWSER_NAME => WebDriverBrowserType::FIREFOX,
      WebDriverCapabilityType::PLATFORM => WebDriverPlatform::ANY,
    ));
  }

  public static function htmlUnit() {
    return new DesiredCapabilities(array(
      WebDriverCapabilityType::BROWSER_NAME => WebDriverBrowserType::HTMLUNIT,
      WebDriverCapabilityType::PLATFORM => WebDriverPlatform::ANY,
    ));
  }

  public static function htmlUnitWithJS() {
    $caps = new DesiredCapabilities(array(
      WebDriverCapabilityType::BROWSER_NAME => WebDriverBrowserType::HTMLUNIT,
      WebDriverCapabilityType::PLATFORM => WebDriverPlatform::ANY,
    ));
    return $caps->setJavascriptEnabled(true);
  }

  public static function internetExplorer() {
    return new DesiredCapabilities(array(
      WebDriverCapabilityType::BROWSER_NAME => WebDriverBrowserType::IE,
      WebDriverCapabilityType::PLATFORM => WebDriverPlatform::WINDOWS,
    ));
  }

  public static function iphone() {
    return new DesiredCapabilities(array(
      WebDriverCapabilityType::BROWSER_NAME => WebDriverBrowserType::IPHONE,
      WebDriverCapabilityType::PLATFORM => WebDriverPlatform::MAC,
    ));
  }

  public static function ipad() {
    return new DesiredCapabilities(array(
      WebDriverCapabilityType::BROWSER_NAME => WebDriverBrowserType::IPAD,
      WebDriverCapabilityType::PLATFORM => WebDriverPlatform::MAC,
    ));
  }

  public static function opera() {
    return new DesiredCapabilities(array(
      WebDriverCapabilityType::BROWSER_NAME => WebDriverBrowserType::OPERA,
      WebDriverCapabilityType::PLATFORM => WebDriverPlatform::ANY,
    ));
  }

  public static function safari() {
    return new DesiredCapabilities(array(
      WebDriverCapabilityType::BROWSER_NAME => WebDriverBrowserType::SAFARI,
      WebDriverCapabilityType::PLATFORM => WebDriverPlatform::ANY,
    ));
  }

  public static function phantomjs() {
    return new DesiredCapabilities(array(
      WebDriverCapabilityType::BROWSER_NAME => WebDriverBrowserType::PHANTOMJS,
      WebDriverCapabilityType::PLATFORM => WebDriverPlatform::ANY,
    ));
  }
}
