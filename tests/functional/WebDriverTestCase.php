<?php declare(strict_types=1);

namespace Facebook\WebDriver;

use Facebook\WebDriver\Chrome\ChromeOptions;
use Facebook\WebDriver\Exception\NoSuchWindowException;
use Facebook\WebDriver\Exception\WebDriverException;
use Facebook\WebDriver\Firefox\FirefoxOptions;
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
    protected $serverUrl = 'http://localhost:4444';
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
            $disableHeadless = filter_var(getenv('DISABLE_HEADLESS') ?: '', FILTER_VALIDATE_BOOLEAN);
            if ($browserName === '' || $browserName === false) {
                $this->markTestSkipped(
                    'To execute functional tests browser name must be provided in BROWSER_NAME environment variable'
                );
            }

            if ($browserName === WebDriverBrowserType::CHROME) {
                $chromeOptions = new ChromeOptions();

                $chromeOptions->addArguments([
                    '--window-size=1024,768',
                    '--no-sandbox', // workaround for https://github.com/SeleniumHQ/selenium/issues/4961
                    '--force-color-profile=srgb',
                    '--disable-search-engine-choice-screen',
                ]);

                if (!$disableHeadless) {
                    $chromeOptions->addArguments(['--headless=new']);
                }

                if (!static::isW3cProtocolBuild()) {
                    $chromeOptions->setExperimentalOption('w3c', false);
                }

                $this->desiredCapabilities->setCapability(ChromeOptions::CAPABILITY, $chromeOptions);
            } elseif ($browserName === WebDriverBrowserType::FIREFOX) {
                $firefoxOptions = new FirefoxOptions();

                if (!$disableHeadless) {
                    $firefoxOptions->addArguments(['-headless']);
                }

                $this->desiredCapabilities->setCapability(FirefoxOptions::CAPABILITY, $firefoxOptions);
            }

            $this->desiredCapabilities->setBrowserName($browserName);
        }

        $this->createWebDriver();
    }

    protected function tearDown(): void
    {
        if ($this->driver !== null) {
            try {
                $this->driver->quit();
            } catch (NoSuchWindowException $e) {
                // browser may have died or is already closed
            }
            $this->driver = null;

            if (getenv('BROWSER_NAME') === 'safari') {
                // The Safari instance is already paired with another WebDriver session
                usleep(200000); // 200ms
            }
        }
    }

    public static function isSauceLabsBuild(): bool
    {
        return getenv('SAUCELABS') ? true : false;
    }

    public static function isW3cProtocolBuild(): bool
    {
        return getenv('DISABLE_W3C_PROTOCOL') !== '1';
    }

    public static function isSeleniumServerUsed(): bool
    {
        return getenv('SELENIUM_SERVER') === '1';
    }

    public static function skipForW3cProtocol($message = 'Not supported by W3C specification'): void
    {
        if (static::isW3cProtocolBuild()) {
            static::markTestSkipped($message);
        }
    }

    public static function skipForJsonWireProtocol($message = 'Not supported by JsonWire protocol'): void
    {
        if (!static::isW3cProtocolBuild()) {
            static::markTestSkipped($message);
        }
    }

    /**
     * Mark a test as skipped if the current browser is not in the list of browsers.
     *
     * @param string[] $browsers List of browsers for this test
     */
    public static function skipForUnmatchedBrowsers(array $browsers = [], ?string $message = null): void
    {
        $browserName = (string) getenv('BROWSER_NAME');
        if (!in_array($browserName, $browsers, true)) {
            if (!$message) {
                $browserList = implode(', ', $browsers);
                $message = 'Browser ' . $browserName . ' not supported for this test (' . $browserList . ')';
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
     */
    protected function getTestPageUrl(string $path): string
    {
        $host = 'http://localhost:8000';
        if ($alternateHost = getenv('FIXTURES_HOST')) {
            $host = $alternateHost;
        }

        return $host . '/' . $path;
    }

    protected function setUpSauceLabs(): void
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

        if (static::isW3cProtocolBuild()) {
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

    protected function createWebDriver(): void
    {
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
