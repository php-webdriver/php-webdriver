<?php

namespace Facebook\WebDriver;

use Facebook\WebDriver\Chrome\ChromeOptions;
use Facebook\WebDriver\Exception\NoSuchWindowException;
use Facebook\WebDriver\Exception\WebDriverException;
use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\Remote\WebDriverBrowserType;
use OndraM\CiDetector\CiDetector;
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

    protected function setUp(): void
    {
        $this->desiredCapabilities = new DesiredCapabilities();

        if (static::isSauceLabsBuild()) {
            $this->setUpSauceLabs();
        } else {
            $browserName = getenv('BROWSER_NAME');
            if ($browserName === '') {
                $this->markTestSkipped(
                    'To execute functional tests browser name must be provided in BROWSER_NAME environment variable'
                );
            }

            if ($browserName === WebDriverBrowserType::CHROME) {
                $chromeOptions = new ChromeOptions();
                // --no-sandbox is a workaround for Chrome crashing: https://github.com/SeleniumHQ/selenium/issues/4961
                $chromeOptions->addArguments(['--headless', 'window-size=1024,768', '--no-sandbox']);

                if (getenv('DISABLE_W3C_PROTOCOL')) {
                    $chromeOptions->setExperimentalOption('w3c', false);
                }

                $this->desiredCapabilities->setCapability(ChromeOptions::CAPABILITY, $chromeOptions);
            } elseif ($browserName === WebDriverBrowserType::FIREFOX) {
                if (getenv('GECKODRIVER') === '1') {
                    $this->serverUrl = 'http://localhost:4444';
                }
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

    protected function tearDown(): void
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
            && (getenv('BROWSER_NAME') !== 'chrome' || getenv('DISABLE_W3C_PROTOCOL') === '1')) {
            static::markTestSkipped($message);
        }
    }

    /**
     * Mark a test as skipped if the current browser is not in the list of browsers.
     *
     * @param string[] $browsers List of browsers for this test
     * @param string|null $message
     */
    public static function skipForUnmatchedBrowsers($browsers = [], $message = null)
    {
        $browserName = (string) getenv('BROWSER_NAME');
        if (array_search($browserName, $browsers) === false) {
            if (!$message) {
                $browserlist = implode(', ', $browsers);
                $message = 'Browser ' . $browserName . ' not supported for this test (' . $browserlist . ')';
            }

            static::markTestSkipped($message);
        }
    }

    /**
     * Rerun failed tests.
     * @TODO Replace with PHPUnit 7.3+ builtin functionality once upgraded to PHP 7.1+
     */
    public function runBare(): void
    {
        $e = null;
        $numberOfRetires = 3;

        for ($i = 0; $i < $numberOfRetires; ++$i) {
            try {
                parent::runBare();

                return;
            } catch (WebDriverException $e) {
                // repeat
            }
        }

        if ($e !== null) {
            throw $e;
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
        $host = 'http://localhost:8000';
        if ($alternateHost = getenv('FIXTURES_HOST')) {
            $host = $alternateHost;
        }

        return $host . '/' . $path;
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

        $ciDetector = new CiDetector();
        if ($ciDetector->isCiDetected()) {
            $ci = $ciDetector->detect();
            if (!empty($ci->getBuildNumber())) {
                // SAUCE_TUNNEL_IDENTIFIER appended as a workaround for GH actions not having environment value
                // to distinguish runs of the matrix
                $build = $ci->getBuildNumber() . '.' . getenv('SAUCE_TUNNEL_IDENTIFIER');
            }
        }

        if (getenv('SAUCE_TUNNEL_IDENTIFIER')) {
            $tunnelIdentifier = getenv('SAUCE_TUNNEL_IDENTIFIER');
        }

        if (!getenv('DISABLE_W3C_PROTOCOL')) {
            $sauceOptions = [
                'name' => $name,
                'tags' => $tags,
            ];
            if (isset($build)) {
                $sauceOptions['build'] = $build;
            }
            if (isset($tunnelIdentifier)) {
                $sauceOptions['tunnelIdentifier'] = $tunnelIdentifier;
            }
            $this->desiredCapabilities->setCapability('sauce:options', (object) $sauceOptions);
        } else {
            $this->desiredCapabilities->setCapability('name', $name);
            $this->desiredCapabilities->setCapability('tags', $tags);

            if (isset($tunnelIdentifier)) {
                $this->desiredCapabilities->setCapability('tunnel-identifier', $tunnelIdentifier);
            }
            if (isset($build)) {
                $this->desiredCapabilities->setCapability('build', $build);
            }
        }
    }

    /**
     * Uses assertStringContainsString when it is available or uses assertContains for old phpunit versions
     * @param string $needle
     * @param string $haystack
     * @param string $message
     */
    protected function compatAssertStringContainsString($needle, $haystack, $message = '')
    {
        if (method_exists($this, 'assertStringContainsString')) {
            parent::assertStringContainsString($needle, $haystack, $message);

            return;
        }
        parent::assertContains($needle, $haystack, $message);
    }
}
