<?php declare(strict_types=1);

namespace Facebook\WebDriver\Firefox;

use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\Remote\Service\DriverCommandExecutor;
use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\WebDriverTestCase;
use PHPUnit\Framework\TestCase;

/**
 * @group exclude-chrome
 * @group exclude-edge
 * @group exclude-safari
 * @group exclude-saucelabs
 * @covers \Facebook\WebDriver\Firefox\FirefoxDriver
 * @covers \Facebook\WebDriver\Local\LocalWebDriver
 */
class FirefoxDriverTest extends TestCase
{
    /** @var FirefoxDriver */
    protected $driver;

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

    public function testShouldStartFirefoxDriver(): void
    {
        $this->startFirefoxDriver();
        $this->assertInstanceOf(FirefoxDriver::class, $this->driver);
        $this->assertInstanceOf(DriverCommandExecutor::class, $this->driver->getCommandExecutor());

        // Make sure actual browser capabilities were set
        $this->assertNotEmpty($this->driver->getCapabilities()->getVersion());
        $this->assertNotEmpty($this->driver->getCapabilities()->getCapability('moz:profile'));
        $this->assertTrue($this->driver->getCapabilities()->getCapability('moz:headless'));

        // Ensure browser is responding to basic command
        $this->driver->get('http://localhost:8000/');
        $this->assertSame('http://localhost:8000/', $this->driver->getCurrentURL());
    }

    public function testShouldSetPreferenceWithFirefoxOptions(): void
    {
        $firefoxOptions = new FirefoxOptions();
        $firefoxOptions->setPreference('javascript.enabled', false);

        $this->startFirefoxDriver($firefoxOptions);

        $this->driver->get('http://localhost:8000/');

        $noScriptElement = $this->driver->findElement(WebDriverBy::id('noscript'));
        $this->assertEquals(
            'This element is only shown with JavaScript disabled.',
            $noScriptElement->getText()
        );
    }

    private function startFirefoxDriver(?FirefoxOptions $firefoxOptions = null): void
    {
        // The createDefaultService() method expect path to the executable to be present in the environment variable
        putenv(FirefoxDriverService::WEBDRIVER_FIREFOX_DRIVER . '=' . getenv('GECKODRIVER_PATH'));

        if ($firefoxOptions === null) {
            $firefoxOptions = new FirefoxOptions();
        }
        $firefoxOptions->addArguments(['-headless']);
        $desiredCapabilities = DesiredCapabilities::firefox();
        $desiredCapabilities->setCapability(FirefoxOptions::CAPABILITY, $firefoxOptions);

        $this->driver = FirefoxDriver::start($desiredCapabilities);
    }
}
