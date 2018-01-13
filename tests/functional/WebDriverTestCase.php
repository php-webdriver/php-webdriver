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

use Facebook\WebDriver\Chrome\ChromeOptions;
use Facebook\WebDriver\Exception\NoSuchWindowException;
use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\Remote\WebDriverBrowserType;
use PHPUnit\Framework\TestCase;

/**
 * The base class for test cases.
 */
class WebDriverTestCase extends TestCase
{
    /** @var RemoteWebDriver $driver */
    public $driver;
    /** @var bool Indicate whether WebDriver should be created on setUp */
    protected $createWebDriver = true;
    /** @var string */
    protected $serverUrl = 'http://localhost:4444/wd/hub';
    /** @var DesiredCapabilities */
    protected $desiredCapabilities;
    /** @var int */
    protected $connectionTimeout = 60000;
    /** @var int */
    protected $requestTimeout = 60000;

    protected function setUp()
    {
        $this->desiredCapabilities = new DesiredCapabilities();

        if ($this->isSauceLabsBuild()) {
            $this->setUpSauceLabs();
        } else {
            if (getenv('BROWSER_NAME')) {
                $browserName = getenv('BROWSER_NAME');
            } else {
                $browserName = WebDriverBrowserType::HTMLUNIT;
            }

            if ($browserName === WebDriverBrowserType::CHROME) {
                $chromeOptions = new ChromeOptions();
                // --no-sandbox is a workaround for Chrome crashing: https://github.com/SeleniumHQ/selenium/issues/4961
                $chromeOptions->addArguments(['--headless', 'window-size=1024,768', '--no-sandbox']);
                $this->desiredCapabilities->setCapability(ChromeOptions::CAPABILITY, $chromeOptions);
            }

            $this->desiredCapabilities->setBrowserName($browserName);
        }

        if ($this->createWebDriver) {
            $this->driver = RemoteWebDriver::create(
                $this->serverUrl,
                $this->desiredCapabilities,
                $this->connectionTimeout,
                $this->requestTimeout
            );
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
     * @return bool
     */
    public function isSauceLabsBuild()
    {
        return getenv('SAUCELABS') ? true : false;
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

    protected function setUpSauceLabs()
    {
        $this->serverUrl = sprintf(
            'http://%s:%s@ondemand.saucelabs.com/wd/hub',
            getenv('SAUCE_USERNAME'),
            getenv('SAUCE_ACCESS_KEY')
        );
        $this->desiredCapabilities->setBrowserName(getenv('BROWSER_NAME'));
        $this->desiredCapabilities->setVersion(getenv('VERSION'));
        $this->desiredCapabilities->setPlatform(getenv('PLATFORM'));
        $this->desiredCapabilities->setCapability('name', get_class($this) . '::' . $this->getName());
        $this->desiredCapabilities->setCapability('tags', [get_class($this)]);

        if (getenv('TRAVIS_JOB_NUMBER')) {
            $this->desiredCapabilities->setCapability('tunnel-identifier', getenv('TRAVIS_JOB_NUMBER'));
            $this->desiredCapabilities->setCapability('build', getenv('TRAVIS_JOB_NUMBER'));
        }
    }
}
