<?php declare(strict_types=1);

namespace Facebook\WebDriver\Chrome;

use Facebook\WebDriver\WebDriverTestCase;
use PHPUnit\Framework\TestCase;

/**
 * @group exclude-saucelabs
 * @covers \Facebook\WebDriver\Chrome\ChromeDriverService
 * @covers \Facebook\WebDriver\Remote\Service\DriverService
 */
class ChromeDriverServiceTest extends TestCase
{
    /** @var ChromeDriverService */
    private $driverService;

    protected function setUp(): void
    {
        if (getenv('BROWSER_NAME') !== 'chrome' || empty(getenv('CHROMEDRIVER_PATH'))
            || WebDriverTestCase::isSauceLabsBuild()) {
            $this->markTestSkipped('The test is run only when running against local chrome');
        }
    }

    protected function tearDown(): void
    {
        if ($this->driverService !== null && $this->driverService->isRunning()) {
            $this->driverService->stop();
        }
    }

    public function testShouldStartAndStopServiceCreatedUsingShortcutConstructor(): void
    {
        // The createDefaultService() method expect path to the executable to be present in the environment variable
        putenv(ChromeDriverService::CHROME_DRIVER_EXECUTABLE . '=' . getenv('CHROMEDRIVER_PATH'));

        $this->driverService = ChromeDriverService::createDefaultService();

        $this->assertSame('http://localhost:9515', $this->driverService->getURL());

        $this->assertInstanceOf(ChromeDriverService::class, $this->driverService->start());
        $this->assertTrue($this->driverService->isRunning());

        $this->assertInstanceOf(ChromeDriverService::class, $this->driverService->start());

        $this->assertInstanceOf(ChromeDriverService::class, $this->driverService->stop());
        $this->assertFalse($this->driverService->isRunning());

        $this->assertInstanceOf(ChromeDriverService::class, $this->driverService->stop());
    }

    public function testShouldStartAndStopServiceCreatedUsingDefaultConstructor(): void
    {
        $this->driverService = new ChromeDriverService(getenv('CHROMEDRIVER_PATH'), 9515, ['--port=9515']);

        $this->assertSame('http://localhost:9515', $this->driverService->getURL());

        $this->driverService->start();
        $this->assertTrue($this->driverService->isRunning());

        $this->driverService->stop();
        $this->assertFalse($this->driverService->isRunning());
    }

    public function testShouldThrowExceptionIfExecutableIsNotExecutable(): void
    {
        putenv(ChromeDriverService::CHROME_DRIVER_EXECUTABLE . '=' . __FILE__);

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('is not executable');
        ChromeDriverService::createDefaultService();
    }

    public function testShouldUseDefaultExecutableIfNoneProvided(): void
    {
        // Put path where ChromeDriver binary is actually located to system PATH, to make sure we can locate it
        putenv('PATH=' . getenv('PATH') . ':' . dirname(getenv('CHROMEDRIVER_PATH')));

        // Unset CHROME_DRIVER_EXECUTABLE so that ChromeDriverService will attempt to run the binary from system PATH
        putenv(ChromeDriverService::CHROME_DRIVER_EXECUTABLE . '=');

        $this->driverService = ChromeDriverService::createDefaultService();

        $this->assertSame('http://localhost:9515', $this->driverService->getURL());

        $this->assertInstanceOf(ChromeDriverService::class, $this->driverService->start());
        $this->assertTrue($this->driverService->isRunning());
    }
}
