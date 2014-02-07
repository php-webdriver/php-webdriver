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

class RemoteWebDriverTest extends PHPUnit_Framework_TestCase {
  public function testCreate() {
    $remoteWebDriver = $this
      ->getMockBuilder('RemoteWebDriver')
      ->disableOriginalConstructor()
      ->setMethods(array('remoteExecuteHttpCommand', 'createHttpCommandExecutor'))
      ->getMock()
    ;

    $timeout = 1000;
    $url = 'http://localhost:4444/wd/hub';
    $response = array('value' => 'someValue', 'info' => 'someInfo', 'sessionId' => 'someSessionId');
    $executor = $this->getMockBuilder('HttpCommandExecutor')->disableOriginalConstructor()->getMock();

    $remoteWebDriver
      ->staticExpects($this->once())
      ->method('remoteExecuteHttpCommand')
      ->with($timeout, array('url' => $url, 'name' => 'newSession', 'parameters' => array('desiredCapabilities' => array())))
      ->will($this->returnValue($response))
    ;

    $remoteWebDriver
      ->staticExpects($this->once())
      ->method('createHttpCommandExecutor')
      ->with($url, $response)
      ->will($this->returnValue($executor))
    ;

    $remoteWebDriver->create($url, array(), $timeout);
  }
}
 