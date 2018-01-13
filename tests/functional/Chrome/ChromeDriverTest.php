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

namespace Facebook\WebDriver\Chrome;

use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\Remote\Service\DriverCommandExecutor;
use PHPUnit\Framework\TestCase;

/**
 * @group exclude-saucelabs
 * @covers \Facebook\WebDriver\Chrome\ChromeDriver
 */
class ChromeDriverTest extends TestCase
{
    /** @var ChromeDriver */
    protected $driver;

    protected function setUp()
    {
        if (!getenv('BROWSER_NAME') === 'chrome' || getenv('SAUCELABS') || !getenv('CHROMEDRIVER_PATH')) {
            $this->markTestSkipped('ChromeDriverServiceTest is run only when running against local chrome');
        }
    }

    protected function tearDown()
    {
        if ($this->driver instanceof RemoteWebDriver && $this->driver->getCommandExecutor() !== null) {
            $this->driver->quit();
        }
    }

    public function testShouldStartChromeDriver()
    {
        // The createDefaultService() method expect path to the executable to be present in the environment variable
        putenv(ChromeDriverService::CHROME_DRIVER_EXE_PROPERTY . '=' . getenv('CHROMEDRIVER_PATH'));

        // Add --no-sandbox as a workaround for Chrome crashing: https://github.com/SeleniumHQ/selenium/issues/4961
        $chromeOptions = new ChromeOptions();
        $chromeOptions->addArguments(['--no-sandbox']);
        $desiredCapabilities = DesiredCapabilities::chrome();
        $desiredCapabilities->setCapability(ChromeOptions::CAPABILITY, $chromeOptions);

        $this->driver = ChromeDriver::start($desiredCapabilities);

        $this->assertInstanceOf(ChromeDriver::class, $this->driver);
        $this->assertInstanceOf(DriverCommandExecutor::class, $this->driver->getCommandExecutor());

        $this->driver->get('http://localhost:8000/');

        $this->assertSame('http://localhost:8000/', $this->driver->getCurrentURL());

        $this->driver->quit();
    }
}
