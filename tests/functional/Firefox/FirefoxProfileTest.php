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
use PHPUnit\Framework\TestCase;

/**
 * @group exclude-saucelabs
 * @covers \Facebook\WebDriver\Firefox\FirefoxProfile
 */
class FirefoxProfileTest extends TestCase
{
    /** @var FirefoxDriver */
    protected $driver;

    protected $firefoxTestExtensionFilename =
        __DIR__ . DIRECTORY_SEPARATOR .
        '..' . DIRECTORY_SEPARATOR .
        'Fixtures/FirefoxWebdriverTestExtension-0.1-fx.xpi';

    protected function setUp(): void
    {
        if (getenv('BROWSER_NAME') !== 'firefox' || empty(getenv('GECKODRIVER_PATH'))
            || WebDriverTestCase::isSauceLabsBuild()) {
            $this->markTestSkipped('The test is run only when running against local firefox');
        }
    }

    protected function tearDown(): void
    {
        if ($this->driver instanceof RemoteWebDriver && $this->driver->getCommandExecutor() !== null) {
            $this->driver->quit();
        }
    }

    public function testShouldInstallExtension()
    {
        $firefoxProfile = new FirefoxProfile();
        $firefoxProfile->addExtension($this->firefoxTestExtensionFilename);
        $this->driver = $this->startFirefoxDriver($firefoxProfile, ['-headless']);

        $this->driver->get($this->getTestPageUrl('index.html'));

        $this->assertInstanceOf(RemoteWebDriver::class, $this->driver);

        $element = $this->driver->findElement(WebDriverBy::id('webDriverExtensionTest'));
        $this->assertInstanceOf(RemoteWebElement::class, $element);
    }

    protected function getTestPageUrl($path)
    {
        $host = 'http://localhost:8000';
        if ($alternateHost = getenv('FIXTURES_HOST')) {
            $host = $alternateHost;
        }

        return $host . '/' . $path;
    }

    private function startFirefoxDriver(FirefoxProfile $firefoxProfile, array $arguments = [])
    {
        // The createDefaultService() method expect path to the executable to be present in the environment variable
        putenv(FirefoxDriverService::WEBDRIVER_FIREFOX_DRIVER . '=' . getenv('GECKODRIVER_PATH'));

        $firefoxOptions = new FirefoxOptions();
        $firefoxOptions->addArguments($arguments);
        $desiredCapabilities = DesiredCapabilities::firefox();
        $desiredCapabilities->setCapability(FirefoxOptions::CAPABILITY, $firefoxOptions);
        $desiredCapabilities->setCapability(FirefoxDriver::PROFILE, $firefoxProfile);

        return FirefoxDriver::start($desiredCapabilities);
    }
}
