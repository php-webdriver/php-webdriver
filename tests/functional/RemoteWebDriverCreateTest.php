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

namespace Facebook\WebDriver;

use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\Remote\HttpCommandExecutor;
use Facebook\WebDriver\Remote\RemoteWebDriver;

/**
 * @covers \Facebook\WebDriver\Remote\RemoteWebDriver
 * @covers \Facebook\WebDriver\Remote\HttpCommandExecutor
 */
class RemoteWebDriverCreateTest extends WebDriverTestCase
{
    protected $createWebDriver = false;

    public function testShouldStartBrowserAndCreateInstanceOfRemoteWebDriver()
    {
        $this->driver = RemoteWebDriver::create(
            $this->serverUrl,
            $this->desiredCapabilities,
            $this->connectionTimeout,
            $this->requestTimeout
        );

        $this->assertInstanceOf(RemoteWebDriver::class, $this->driver);

        $this->assertInstanceOf(HttpCommandExecutor::class, $this->driver->getCommandExecutor());
        $this->assertNotEmpty($this->driver->getCommandExecutor()->getAddressOfRemoteServer());

        $this->assertInternalType('string', $this->driver->getSessionID());
        $this->assertNotEmpty($this->driver->getSessionID());

        $returnedCapabilities = $this->driver->getCapabilities();
        $this->assertInstanceOf(WebDriverCapabilities::class, $returnedCapabilities);
        $this->assertSame($this->desiredCapabilities->getBrowserName(), $returnedCapabilities->getBrowserName());
    }

    public function testShouldCreateWebDriverWithRequiredCapabilities()
    {
        $requiredCapabilities = new DesiredCapabilities();

        $this->driver = RemoteWebDriver::create(
            $this->serverUrl,
            $this->desiredCapabilities,
            $this->connectionTimeout,
            $this->requestTimeout,
            null,
            null,
            $requiredCapabilities
        );

        $this->assertInstanceOf(RemoteWebDriver::class, $this->driver);
    }

    public function testShouldCreateInstanceFromExistingSessionId()
    {
        // Create driver instance and load page "index.html"
        $originalDriver = RemoteWebDriver::create(
            $this->serverUrl,
            $this->desiredCapabilities,
            $this->connectionTimeout,
            $this->requestTimeout
        );
        $originalDriver->get($this->getTestPageUrl('index.html'));
        $this->assertContains('/index.html', $originalDriver->getCurrentURL());

        // Store session ID
        $sessionId = $originalDriver->getSessionID();

        // Create new RemoteWebDriver instance based on the session ID
        $this->driver = RemoteWebDriver::createBySessionID($sessionId, $this->serverUrl);

        // Check we reused the previous instance (window) and it has the same URL
        $this->assertContains('/index.html', $this->driver->getCurrentURL());
    }
}
