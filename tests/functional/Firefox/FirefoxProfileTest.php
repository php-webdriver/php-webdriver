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

namespace Facebook\WebDriver\Firefox;

use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\Remote\RemoteWebElement;
use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\WebDriverTestCase;

/**
 * @group firefox
 * @covers \Facebook\WebDriver\Firefox\FirefoxProfile
 */
class FirefoxProfileTest extends WebDriverTestCase
{
    protected $firefoxTestExtensionFilename =
        __DIR__ . DIRECTORY_SEPARATOR .
        '..' . DIRECTORY_SEPARATOR .
        'Fixtures/FirefoxWebdriverTestExtension-0.1-fx.xpi';

    protected function setUp()
    {
        if (getenv('BROWSER_NAME') !== 'firefox' || getenv('SAUCELABS')) {
            $this->markTestSkipped('FirefoxProfileTest is run only when running against local firefox');
        }
    }

    public function testShouldInstallExtension()
    {
        $this->desiredCapabilities = DesiredCapabilities::firefox();

        $firefoxProfile = new FirefoxProfile();
        $firefoxProfile->addExtension($this->firefoxTestExtensionFilename);

        $this->desiredCapabilities->setCapability(FirefoxDriver::PROFILE, $firefoxProfile);

        $this->driver = RemoteWebDriver::create(
            $this->serverUrl,
            $this->desiredCapabilities,
            $this->connectionTimeout,
            $this->requestTimeout
        );

        $this->driver->get($this->getTestPageUrl('index.html'));

        $this->assertInstanceOf(RemoteWebDriver::class, $this->driver);

        $element = $this->driver->findElement(WebDriverBy::id('webDriverExtensionTest'));
        $this->assertInstanceOf(RemoteWebElement::class, $element);

        $this->driver->quit();
    }

    public function testShouldInstallExtensionInHeadlessMode()
    {
        $this->desiredCapabilities = DesiredCapabilities::firefox();

        $firefoxProfile = new FirefoxProfile();
        $firefoxProfile->addExtension($this->firefoxTestExtensionFilename);

        $this->desiredCapabilities->setCapability(FirefoxDriver::PROFILE, $firefoxProfile);

        $arguments[] = '--headless';
        $this->desiredCapabilities->setCapability('moz:firefoxOptions', ['args' => $arguments]);

        $this->driver = RemoteWebDriver::create(
            $this->serverUrl,
            $this->desiredCapabilities,
            $this->connectionTimeout,
            $this->requestTimeout
        );

        $this->driver->get($this->getTestPageUrl('index.html'));

        $this->assertInstanceOf(RemoteWebDriver::class, $this->driver);

        $element = $this->driver->findElement(WebDriverBy::id('webDriverExtensionTest'));
        $this->assertInstanceOf(RemoteWebElement::class, $element);

        $this->driver->quit();
    }
}
