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

        if (static::isSauceLabsBuild()) {
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

                if (getenv('DISABLE_W3C_PROTOCOL')) {
                    $chromeOptions->setExperimentalOption('w3c', false);
                }

                $this->desiredCapabilities->setCapability(ChromeOptions::CAPABILITY, $chromeOptions);
            } elseif (getenv('GECKODRIVER') === '1') {
                $this->serverUrl = 'http://localhost:4444';
                $this->desiredCapabilities->setCapability(
                    'moz:firefoxOptions',
                    ['args' => ['-headless']]
                );
            }

            $this->desiredCapabilities->setBrowserName($browserName);
        }

        if ($this->createWebDriver) {
            $this->driver = RemoteWebDriver::create(
                $this->serverUrl,
                $this->desiredCapabilities,
                $this->connectionTimeout,
                $this->requestTimeout,
                null,
                null,
                null
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
    public static function isSauceLabsBuild()
    {
        return getenv('SAUCELABS') ? true : false;
    }

    /**
     * @return bool
     */
    public static function isW3cProtocolBuild()
    {
        return getenv('GECKODRIVER') === '1'
            || (getenv('BROWSER_NAME') === 'chrome' && getenv('DISABLE_W3C_PROTOCOL') !== '1')
            || (self::isSauceLabsBuild() && getenv('DISABLE_W3C_PROTOCOL') !== '1');
    }

    public static function skipForW3cProtocol($message = 'Not supported by W3C specification')
    {
        if (static::isW3cProtocolBuild()) {
            static::markTestSkipped($message);
        }
    }

    public static function skipForJsonWireProtocol($message = 'Not supported by JsonWire protocol')
    {
        if (getenv('GECKODRIVER') !== '1'
            && (getenv('CHROMEDRIVER') !== '1' || getenv('DISABLE_W3C_PROTOCOL') === '1')) {
            static::markTestSkipped($message);
        }
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
        $name = get_class($this) . '::' . $this->getName();
        $tags = [get_class($this)];

        if (getenv('TRAVIS_JOB_NUMBER')) {
            $tunnelIdentifier = getenv('TRAVIS_JOB_NUMBER');
            $build = getenv('TRAVIS_JOB_NUMBER');
        }

        if (!getenv('DISABLE_W3C_PROTOCOL')) {
            $sauceOptions = [
                'name' => $name,
                'tags' => $tags,
            ];
            if (isset($tunnelIdentifier, $build)) {
                $sauceOptions['tunnelIdentifier'] = $tunnelIdentifier;
                $sauceOptions['build'] = $build;
            }
            $this->desiredCapabilities->setCapability('sauce:options', (object) $sauceOptions);
        } else {
            $this->desiredCapabilities->setCapability('name', $name);
            $this->desiredCapabilities->setCapability('tags', $tags);

            if (isset($tunnelIdentifier, $build)) {
                $this->desiredCapabilities->setCapability('tunnel-identifier', $tunnelIdentifier);
                $this->desiredCapabilities->setCapability('build', $build);
            }
        }
    }
}
