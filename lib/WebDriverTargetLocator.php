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
 * Used to locate a given frame or window.
 */
class WebDriverTargetLocator {

  protected $executor;
  protected $driver;

  public function __construct($executor, $driver) {
    $this->executor = $executor;
    $this->driver = $driver;
  }

  /**
   * Switch to the main document if the page contains iframes. Otherwise, switch
   * to the first frame on the page.
   *
   * @return WebDriver The driver focused on the top window or the first frame.
   */
  public function defaultContent() {
    $this->executor->execute('focusFrame', array());

    return $this->driver;
  }

  /**
   * Switch to the iframe by its id or name.
   *
   * @return WebDriver The driver focused on the given frame.
   */
  public function frame($id_or_name) {
    $params = array('id' => (string)$id_or_name);
    $this->executor->execute('focusFrame', $params);

    return $this->driver;
  }

  /**
   * Switch the focus to another window by its handle.
   *
   * @param string $handle The handle of the window to be focused on.
   * @return WebDriver Tge driver focused on the given window.
   * @see WebDriver::getWindowHandles
   */
  public function window($handle) {
    $params = array('name' => (string)$handle);
    $this->executor->execute('focusWindow', $params);

    return $this->driver;
  }

  /**
   * Switch to the currently active modal dialog for this particular driver
   * instance.
   *
   * @return WebDriverAlert
   */
  public function alert() {
    return new WebDriverAlert($this->executor);
  }
}
