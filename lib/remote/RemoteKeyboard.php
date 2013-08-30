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

/**
 * Execute keyboard commands for RemoteWebDriver.
 */
class RemoteKeyboard implements WebDriverKeyboard {

  private $executor;

  protected $modifiers = array('control','shift','alt','command','meta');

  public function __construct($executor) {
    $this->executor = $executor;
  }

  /**
   * Send keys to active element
   *
   * @param $keys
   * @return $this
   */
  public function sendKeys($keys) {
    $this->sendKeysToActiveElement(WebDriverKeys::encode(func_get_args()));
    return $this;
  }

  /**
   * Press a modifier key
   *
   * @see WebDriverKeys
   * @param $key
   * @return $this
   */
  public function press($key)
  {
    $this->sendKeysToActiveElement($this->getModifierKey($key));
    return $this;
  }

  /**
   * Release a modifier key
   *
   * @see WebDriverKeys
   * @param $key
   * @return $this
   */
  public function release($key)
  {
    $this->sendKeysToActiveElement($this->getModifierKey($key));
    return $this;
  }

  private function sendKeysToActiveElement($value)
  {
    $params = array(
      'value' => $value
    );
    $this->executor->execute('sendKeysToActiveElement', $params);   
  }    

  private function getModifierKey($key)
  {
    if (!in_array($key, $this->modifiers))
      throw new InvalidArgumentException("$key is not a modifier key, expected one of ".implode(', ',$this->modifiers));
    return array(constant('WebDriverKeys::'.strtoupper($key)));
  }
}