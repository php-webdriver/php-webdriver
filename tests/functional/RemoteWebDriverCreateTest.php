<?php declare(strict_types=1);

namespace Facebook\WebDriver;

use Facebook\WebDriver\Exception\Internal\UnexpectedResponseException;
use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\Remote\HttpCommandExecutor;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\Remote\WebDriverBrowserType;

/**
 * @covers \Facebook\WebDriver\Exception\Internal\UnexpectedResponseException
 * @covers \Facebook\WebDriver\Remote\HttpCommandExecutor
 * @covers \Facebook\WebDriver\Remote\RemoteWebDriver
 */
class RemoteWebDriverCreateTest extends WebDriverTestCase
{
    public function testShouldStartBrowserAndCreateInstanceOfRemoteWebDriver(): void
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

        $this->assertInstanceOf(RemoteWebDriver::class, $this->driver);

        $this->assertInstanceOf(HttpCommandExecutor::class, $this->driver->getCommandExecutor());
        $this->assertNotEmpty($this->driver->getCommandExecutor()->getAddressOfRemoteServer());

        $this->assertIsString($this->driver->getSessionID());
        $this->assertNotEmpty($this->driver->getSessionID());

        $returnedCapabilities = $this->driver->getCapabilities();
        $this->assertInstanceOf(WebDriverCapabilities::class, $returnedCapabilities);

        // MicrosoftEdge on Sauce Labs started to identify itself back as "msedge"
        if ($this->desiredCapabilities->getBrowserName() !== WebDriverBrowserType::MICROSOFT_EDGE) {
            $this->assertEqualsIgnoringCase(
                $this->desiredCapabilities->getBrowserName(),
                $returnedCapabilities->getBrowserName()
            );
        }

        $this->assertNotEmpty($returnedCapabilities->getPlatform());
        $this->assertNotEmpty($returnedCapabilities);
        $this->assertNotEmpty($returnedCapabilities->getVersion());
    }

    public function testShouldAcceptCapabilitiesAsAnArray(): void
    {
        // Method has a side-effect of converting whole content of desiredCapabilities to an array
        $this->desiredCapabilities->toArray();

        $this->driver = RemoteWebDriver::create(
            $this->serverUrl,
            $this->desiredCapabilities,
            $this->connectionTimeout,
            $this->requestTimeout
        );

        $this->assertNotNull($this->driver->getCapabilities());
    }

    public function testShouldCreateWebDriverWithRequiredCapabilities(): void
    {
        $requiredCapabilities = new DesiredCapabilities();

        $this->driver = RemoteWebDriver::create(
            $this->serverUrl,
            $this->desiredCapabilities,
            $this->connectionTimeout,
            $this->requestTimeout,
            null,
            null,
            $requiredCapabilities
        );

        $this->assertInstanceOf(RemoteWebDriver::class, $this->driver);
    }

    /**
     * Capabilities (browser name) must be defined when executing via Selenium proxy (standalone server,
     * Saucelabs etc.). But when running directly via browser driver, they could be empty.
     * However the the browser driver must be able to create non-headless instance (eg. inside xvfb).
     * @group exclude-saucelabs
     */
    public function testShouldCreateWebDriverWithoutCapabilities(): void
    {
        if (getenv('GECKODRIVER') !== '1' && empty(getenv('CHROMEDRIVER_PATH'))) {
            $this->markTestSkipped('This test makes sense only when run directly via specific browser driver');
        }

        $this->driver = RemoteWebDriver::create($this->serverUrl);

        $this->assertInstanceOf(RemoteWebDriver::class, $this->driver);
        $this->assertNotEmpty($this->driver->getSessionID());
    }

    public function testShouldCreateInstanceFromExistingSessionId(): void
    {
        // Create driver instance and load page "index.html"
        $originalDriver = RemoteWebDriver::create(
            $this->serverUrl,
            $this->desiredCapabilities,
            $this->connectionTimeout,
            $this->requestTimeout
        );
        $originalDriver->get($this->getTestPageUrl(TestPage::INDEX));
        $this->assertStringContainsString('/index.html', $originalDriver->getCurrentURL());

        // Store session attributes
        $sessionId = $originalDriver->getSessionID();
        $isW3cCompliant = $originalDriver->isW3cCompliant();
        $originalCapabilities = $originalDriver->getCapabilities();

        $capabilitiesForSessionReuse = $originalCapabilities;
        if ($this->isSeleniumServerUsed()) {
            // do not provide capabilities when selenium server is used, to test they are read from selenium server
            $capabilitiesForSessionReuse = null;
        }

        // Create new RemoteWebDriver instance based on the session ID
        $this->driver = RemoteWebDriver::createBySessionID(
            $sessionId,
            $this->serverUrl,
            null,
            null,
            $isW3cCompliant,
            $capabilitiesForSessionReuse
        );

        // Capabilities should be retrieved and be set to the driver instance
        $returnedCapabilities = $this->driver->getCapabilities();
        $this->assertInstanceOf(WebDriverCapabilities::class, $returnedCapabilities);

        $expectedBrowserName = $this->desiredCapabilities->getBrowserName();

        $this->assertEqualsIgnoringCase(
            $expectedBrowserName,
            $returnedCapabilities->getBrowserName()
        );
        $this->assertEqualsCanonicalizing($originalCapabilities, $this->driver->getCapabilities());

        // Check we reused the previous instance (window) and it has the same URL
        $this->assertStringContainsString('/index.html', $this->driver->getCurrentURL());

        // Do some interaction with the new driver
        $this->assertNotEmpty($this->driver->findElement(WebDriverBy::id('id_test'))->getText());
    }

    public function testShouldRequireCapabilitiesToBeSetToReuseExistingSession(): void
    {
        $this->expectException(UnexpectedResponseException::class);
        $this->expectExceptionMessage(
            'Existing Capabilities were not provided, and they also cannot be read from Selenium Grid'
        );

        // Do not provide capabilities, they also cannot be retrieved from the Selenium Grid
        RemoteWebDriver::createBySessionID(
            'sessionId',
            'http://localhost:332', // nothing should be running there
            null,
            null
        );
    }

    protected function createWebDriver(): void
    {
    }
}
