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
 * Execute touch commands for RemoteWebDriver.
 */
class RemoteTouchScreen implements WebDriverTouchScreen {

  private $executor;

  public function __construct($executor) {
    $this->executor = $executor;
  }

  /**
   * @return RemoteTouchScreen The instance.
   */
  public function tap(WebDriverElement $element) {
    $this->executor->execute('touchTap', array('element' => $element->getID()));

    return $this;
  }

  /**
   * @return RemoteTouchScreen The instance.
   */
  public function doubleTap(WebDriverElement $element) {
    $this->executor->execute(
      'touchDoubleTap',
      array('element' => $element->getID())
    );

    return $this;
  }

  /**
   * @return RemoteTouchScreen The instance.
   */
  public function down($x, $y) {
    $this->executor->execute('touchDown', array(
      'x' => $x,
      'y' => $y,
    ));

    return $this;
  }


  /**
   * @return RemoteTouchScreen The instance.
   */
  public function flick($xspeed, $yspeed) {
    $this->executor->execute('touchFlick', array(
      'xspeed' => $xspeed,
      'yspeed' => $yspeed,
    ));

    return $this;
  }

  /**
   * @return RemoteTouchScreen The instance.
   */
  public function flickFromElement(
    WebDriverElement $element, $xoffset, $yoffset, $speed
  ) {
    $this->executor->execute('touchFlick', array(
      'xoffset' => $xoffset,
      'yoffset' => $yoffset,
      'element' => $element->getID(),
      'speed'   => $speed,
    ));

    return $this;
  }

  /**
   * @return RemoteTouchScreen The instance.
   */
  public function longPress(WebDriverElement $element) {
    $this->executor->execute(
      'touchLongPress',
      array('element' => $element->getID())
    );

    return $this;
  }

  /**
   * @return RemoteTouchScreen The instance.
   */
  public function move($x, $y) {
    $this->executor->execute('touchMove', array(
      'x' => $x,
      'y' => $y,
    ));

    return $this;
  }

  /**
   * @return RemoteTouchScreen The instance.
   */
  public function scroll($xoffset, $yoffset) {
    $this->executor->execute('touchScroll', array(
      'xoffset' => $xoffset,
      'yoffset' => $yoffset,
    ));

    return $this;
  }

  /**
   * @return RemoteTouchScreen The instance.
   */
  public function scrollFromElement(
    WebDriverElement $element, $xoffset, $yoffset
  ) {
    $this->executor->execute('touchScroll', array(
      'element' => $element->getID(),
      'xoffset' => $xoffset,
      'yoffset' => $yoffset,
    ));

    return $this;
  }


  /**
   * @return RemoteTouchScreen The instance.
   */
  public function up($x, $y) {
    $this->executor->execute('touchUp', array(
      'x' => $x,
      'y' => $y,
    ));

    return $this;
  }

}
