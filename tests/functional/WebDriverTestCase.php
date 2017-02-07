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

use Facebook\WebDriver\Exception\NoSuchWindowException;
use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\Remote\WebDriverBrowserType;

/**
 * The base class for test cases.
 */
class WebDriverTestCase extends \PHPUnit_Framework_TestCase
{
    /** @var bool Indicate whether WebDriver should be created on setUp */
    protected $createWebDriver = true;
    /** @var string */
    protected $serverUrl = 'http://localhost:4444/wd/hub';
    /** @var RemoteWebDriver $driver */
    protected $driver;
    /** @var DesiredCapabilities */
    protected $desiredCapabilities;

    protected function setUp()
    {
        $this->desiredCapabilities = new DesiredCapabilities();

        if (getenv('BROWSER_NAME')) {
            $browserName = getenv('BROWSER_NAME');
        } else {
            $browserName = WebDriverBrowserType::HTMLUNIT;
        }

        $this->desiredCapabilities->setBrowserName($browserName);

        if ($this->createWebDriver) {
            $this->driver = RemoteWebDriver::create($this->serverUrl, $this->desiredCapabilities);
        }
    }

    protected function tearDown()
    {
        if ($this->driver instanceof RemoteWebDriver && $this->driver->getCommandExecutor()) {
            try {
                $this->driver->quit();
            } catch (NoSuchWindowException $e) {
                // browser may have died or is already closed
            }
        }
    }

    /**
     * Get the URL of the test html on filesystem.
     *
     * @param $path
     * @return string
     */
    protected function getTestPath($path)
    {
        return 'file:///' . __DIR__ . '/web/' . $path;
    }

    /**
     * Get the URL of given test HTML on running webserver.
     *
     * @param string $path
     * @return string
     */
    protected function getTestPageUrl($path)
    {
        return 'http://localhost:8000/' . $path;
    }
}
