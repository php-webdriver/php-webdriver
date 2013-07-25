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
 * An abstraction allowing the driver to manipulate the browser's window
 */
class WebDriverWindow {

  protected $executor;

  public function __construct($executor) {
    $this->executor = $executor;
  }

  /**
   * Get the position of the current window, relative to the upper left corner
   * of the screen.
   *
   * @return array The current window position.
   */
  public function getPosition() {
    $position = $this->executor->execute(
      'getWindowPosition',
      array(':windowHandle' => 'current')
    );
    return new WebDriverPoint(
      $position['x'],
      $position['y']
    );
  }

  /**
   * Get the size of the current window. This will return the outer window
   * dimension, not just the view port.
   *
   * @return array The current window size.
   */
  public function getSize() {
    $size = $this->executor->execute(
      'getWindowSize',
      array(':windowHandle' => 'current')
    );
    return new WebDriverDimension(
      $size['width'],
      $size['height']
    );
  }

  /**
   * Maximizes the current window if it is not already maximized
   *
   * @return WebDriverWindow The instance.
   */
  public function maximize() {
    $this->executor->execute(
      'maximizeWindow',
      array(':windowHandle' => 'current')
    );
    return $this;
  }

  /**
   * Set the size of the current window. This will change the outer window
   * dimension, not just the view port.
   *
   * @param int $width The target window width.
   * @param int $height The target height height.
   * @return WebDriverWindow The instance.
   */
  public function setSize(WebDriverDimension $size) {
    $params = array(
      'width' => $size->getWidth(),
      'height' => $size->getHeight(),
      ':windowHandle' => 'current',
    );
    $this->executor->execute('setWindowSize', $params);
    return $this;
  }

  /**
   * Set the position of the current window. This is relative to the upper left
   * corner of the screen.
   *
   * @param int $width The target window width.
   * @param int $height The target height height.
   * @return WebDriverWindow The instance.
   */
  public function setPosition(WebDriverPoint $position) {
    $params = array(
      'x' => $position->getX(),
      'y' => $position->getY(),
      ':windowHandle' => 'current',
    );
    $this->executor->execute('setWindowPosition', $params);
    return $this;
  }
}
