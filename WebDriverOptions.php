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
 * Managing stuff you would do in a browser.
 */
class WebDriverOptions {

  protected $executor;
  protected $sessionID;

  public function __construct($executor, $session_id) {
    $this->executor = $executor;
    $this->sessionID = $session_id;
  }

  /**
   * Return the interface for managing driver timeouts.
   *
   * @return WebDriverTimeouts
   */
  public function timeouts() {
    return new WebDriverTimeouts($this->executor, $this->sessionID);
  }

  /**
   * An abstraction allowing the driver to manipulate the browser's window
   *
   * @return WebDriverWindow
   * @see WebDriverWindow
   */
  public function window() {
    return new WebDriverWindow(
      $this->executor,
      $this->sessionID
    );
  }

  private function execute($name, array $params = array()) {
    $command = array(
      'sessionId' => $this->sessionID,
      'name' => $name,
      'parameters' => $params,
    );
    $raw = $this->executor->execute($command);
    return $raw['value'];
  }
}
