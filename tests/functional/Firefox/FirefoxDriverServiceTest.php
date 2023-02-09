<?php declare(strict_types=1);

namespace Facebook\WebDriver\Firefox;

use Facebook\WebDriver\WebDriverTestCase;
use PHPUnit\Framework\TestCase;

/**
 * @group exclude-chrome
 * @group exclude-edge
 * @group exclude-safari
 * @group exclude-saucelabs
 * @covers \Facebook\WebDriver\Firefox\FirefoxDriverService
 */
class FirefoxDriverServiceTest extends TestCase
{
    /** @var FirefoxDriverService */
    private $driverService;

    protected function setUp(): void
    {
        if (getenv('BROWSER_NAME') !== 'firefox' || empty(getenv('GECKODRIVER_PATH'))
            || WebDriverTestCase::isSauceLabsBuild()) {
            $this->markTestSkipped('The test is run only when running against local firefox');
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
        putenv(FirefoxDriverService::WEBDRIVER_FIREFOX_DRIVER . '=' . getenv('GECKODRIVER_PATH'));

        $this->driverService = FirefoxDriverService::createDefaultService();

        $this->assertSame('http://localhost:9515', $this->driverService->getURL());

        $this->assertInstanceOf(FirefoxDriverService::class, $this->driverService->start());
        $this->assertTrue($this->driverService->isRunning());

        $this->assertInstanceOf(FirefoxDriverService::class, $this->driverService->start());

        $this->assertInstanceOf(FirefoxDriverService::class, $this->driverService->stop());
        $this->assertFalse($this->driverService->isRunning());

        $this->assertInstanceOf(FirefoxDriverService::class, $this->driverService->stop());
    }

    public function testShouldStartAndStopServiceCreatedUsingDefaultConstructor(): void
    {
        $this->driverService = new FirefoxDriverService(getenv('GECKODRIVER_PATH'), 9515, ['-p=9515']);

        $this->assertSame('http://localhost:9515', $this->driverService->getURL());

        $this->driverService->start();
        $this->assertTrue($this->driverService->isRunning());

        $this->driverService->stop();
        $this->assertFalse($this->driverService->isRunning());
    }

    public function testShouldUseDefaultExecutableIfNoneProvided(): void
    {
        // Put path where geckodriver binary is actually located to system PATH, to make sure we can locate it
        putenv('PATH=' . getenv('PATH') . ':' . dirname(getenv('GECKODRIVER_PATH')));

        // Unset WEBDRIVER_FIREFOX_BINARY so that FirefoxDriverService will attempt to run the binary from system PATH
        putenv(FirefoxDriverService::WEBDRIVER_FIREFOX_DRIVER . '=');

        $this->driverService = FirefoxDriverService::createDefaultService();

        $this->assertSame('http://localhost:9515', $this->driverService->getURL());

        $this->assertInstanceOf(FirefoxDriverService::class, $this->driverService->start());
        $this->assertTrue($this->driverService->isRunning());
    }
}
