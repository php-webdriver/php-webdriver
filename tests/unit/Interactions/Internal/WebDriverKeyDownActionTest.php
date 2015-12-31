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

namespace Facebook\WebDriver\Interactions\Internal;

use Facebook\WebDriver\Internal\WebDriverLocatable;
use Facebook\WebDriver\WebDriverKeyboard;
use Facebook\WebDriver\WebDriverMouse;

class WebDriverKeyDownActionTest extends \PHPUnit_Framework_TestCase {
  /** @var WebDriverKeyDownAction */
  private $webDriverKeyDownAction;
  /** @var WebDriverKeyboard|\PHPUnit_Framework_MockObject_MockObject */
  private $webDriverKeyboard;
  /** @var WebDriverMouse|\PHPUnit_Framework_MockObject_MockObject */
  private $webDriverMouse;
  /** @var WebDriverLocatable|\PHPUnit_Framework_MockObject_MockObject  */
  private $locationProvider;

  public function setUp() {
    $this->webDriverKeyboard = $this->getMock('Facebook\WebDriver\WebDriverKeyboard');
    $this->webDriverMouse = $this->getMock('Facebook\WebDriver\WebDriverMouse');
    $this->locationProvider = $this->getMock('Facebook\WebDriver\Internal\WebDriverLocatable');

    $this->webDriverKeyDownAction = new WebDriverKeyDownAction(
      $this->webDriverKeyboard,
      $this->webDriverMouse,
      $this->locationProvider
    );
  }

  public function testPerformFocusesOnElementAndSendPressKeyCommand() {
    $coords = $this->getMockBuilder('Facebook\WebDriver\Interactions\Internal\WebDriverCoordinates')
      ->disableOriginalConstructor()->getMock();
    $this->webDriverMouse->expects($this->once())->method('click')->with($coords);
    $this->locationProvider->expects($this->once())->method('getCoordinates')->will($this->returnValue($coords));
    $this->webDriverKeyboard->expects($this->once())->method('pressKey');
    $this->webDriverKeyDownAction->perform();
  }
}
