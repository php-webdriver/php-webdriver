<?php

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

    protected function setUp(): void
    {
        if (!getenv('BROWSER_NAME') === 'chrome' || getenv('SAUCELABS') || !getenv('CHROMEDRIVER_PATH')) {
            $this->markTestSkipped('ChromeDriverServiceTest is run only when running against local chrome');
        }
    }

    protected function tearDown(): void
    {
        if ($this->driver instanceof RemoteWebDriver && $this->driver->getCommandExecutor() !== null) {
            $this->driver->quit();
        }
    }

    public function testShouldStartChromeDriver()
    {
        // The createDefaultService() method expect path to the executable to be present in the environment variable
        putenv(ChromeDriverService::CHROME_DRIVER_EXECUTABLE . '=' . getenv('CHROMEDRIVER_PATH'));

        // Add --no-sandbox as a workaround for Chrome crashing: https://github.com/SeleniumHQ/selenium/issues/4961
        $chromeOptions = new ChromeOptions();
        $chromeOptions->addArguments(['--no-sandbox', '--headless']);
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
