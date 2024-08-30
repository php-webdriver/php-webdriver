<?php declare(strict_types=1);

namespace Facebook\WebDriver\Chrome;

use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\Remote\Service\DriverCommandExecutor;
use Facebook\WebDriver\WebDriverTestCase;
use PHPUnit\Framework\TestCase;

/**
 * @group exclude-saucelabs
 * @covers \Facebook\WebDriver\Chrome\ChromeDriver
 * @covers \Facebook\WebDriver\Local\LocalWebDriver
 */
class ChromeDriverTest extends TestCase
{
    /** @var ChromeDriver */
    protected $driver;

    protected function setUp(): void
    {
        if (getenv('BROWSER_NAME') !== 'chrome' || empty(getenv('CHROMEDRIVER_PATH'))
            || WebDriverTestCase::isSauceLabsBuild()) {
            $this->markTestSkipped('The test is run only when running against local chrome');
        }
    }

    protected function tearDown(): void
    {
        if ($this->driver instanceof RemoteWebDriver && $this->driver->getCommandExecutor() !== null) {
            $this->driver->quit();
        }
    }

    /**
     * @dataProvider provideDialect
     */
    public function testShouldStartChromeDriver(bool $isW3cDialect): void
    {
        $this->startChromeDriver($isW3cDialect);
        $this->assertInstanceOf(ChromeDriver::class, $this->driver);
        $this->assertInstanceOf(DriverCommandExecutor::class, $this->driver->getCommandExecutor());

        // Make sure actual browser capabilities were set
        $this->assertNotEmpty($this->driver->getCapabilities()->getVersion());
        $this->assertNotEmpty($this->driver->getCapabilities()->getCapability('goog:chromeOptions'));

        $this->driver->get('http://localhost:8000/');

        $this->assertSame('http://localhost:8000/', $this->driver->getCurrentURL());
    }

    /**
     * @return array[]
     */
    public function provideDialect(): array
    {
        return [
            'w3c' => [true],
            'oss' => [false],
        ];
    }

    public function testShouldInstantiateDevTools(): void
    {
        $this->startChromeDriver();

        $devTools = $this->driver->getDevTools();

        $this->assertInstanceOf(ChromeDevToolsDriver::class, $devTools);

        $this->driver->get('http://localhost:8000/');

        $cdpResult = $devTools->execute(
            'Runtime.evaluate',
            ['expression' => 'window.location.toString()']
        );

        $this->assertSame(['result' => ['type' => 'string', 'value' => 'http://localhost:8000/']], $cdpResult);
    }

    private function startChromeDriver($w3cDialect = true): void
    {
        // The createDefaultService() method expect path to the executable to be present in the environment variable
        putenv(ChromeDriverService::CHROME_DRIVER_EXECUTABLE . '=' . getenv('CHROMEDRIVER_PATH'));

        // Add --no-sandbox as a workaround for Chrome crashing: https://github.com/SeleniumHQ/selenium/issues/4961
        $chromeOptions = new ChromeOptions();
        $chromeOptions->addArguments(['--no-sandbox', '--headless=new', '--disable-search-engine-choice-screen']);
        $chromeOptions->setExperimentalOption('w3c', $w3cDialect);
        $desiredCapabilities = DesiredCapabilities::chrome();
        $desiredCapabilities->setCapability(ChromeOptions::CAPABILITY, $chromeOptions);

        $this->driver = ChromeDriver::start($desiredCapabilities);
    }
}
