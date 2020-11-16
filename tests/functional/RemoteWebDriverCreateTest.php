<?php

namespace Facebook\WebDriver;

use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\Remote\HttpCommandExecutor;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\Remote\WebDriverBrowserType;

/**
 * @covers \Facebook\WebDriver\Remote\HttpCommandExecutor
 * @covers \Facebook\WebDriver\Remote\RemoteWebDriver
 */
class RemoteWebDriverCreateTest extends WebDriverTestCase
{
    protected $createWebDriver = false;

    public function testShouldStartBrowserAndCreateInstanceOfRemoteWebDriver()
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

        $this->assertTrue(is_string($this->driver->getSessionID()));
        $this->assertNotEmpty($this->driver->getSessionID());

        $returnedCapabilities = $this->driver->getCapabilities();
        $this->assertInstanceOf(WebDriverCapabilities::class, $returnedCapabilities);

        // MicrosoftEdge on Sauce Labs started to identify itself back as "msedge"
        if ($this->desiredCapabilities->getBrowserName() !== WebDriverBrowserType::MICROSOFT_EDGE) {
            $this->assertSame($this->desiredCapabilities->getBrowserName(), $returnedCapabilities->getBrowserName());
        }

        $this->assertNotEmpty($returnedCapabilities->getPlatform());
        $this->assertNotEmpty($returnedCapabilities);
        $this->assertNotEmpty($returnedCapabilities->getVersion());
    }

    public function testShouldAcceptCapabilitiesAsAnArray()
    {
        // Method has a side-effect of converting whole content of desiredCapabilities to an array
        $this->desiredCapabilities->toArray();

        $this->driver = RemoteWebDriver::create(
            $this->serverUrl,
            $this->desiredCapabilities,
            $this->connectionTimeout,
            $this->requestTimeout
        );
    }

    public function testShouldCreateWebDriverWithRequiredCapabilities()
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
     * Capabilities (browser name) must be defined when executing via Selenium proxy (standalone server, Saucelabs etc.)
     * @group exclude-saucelabs
     */
    public function testShouldCreateWebDriverWithoutCapabilities()
    {
        if (getenv('GECKODRIVER') !== '1' && getenv('CHROMEDRIVER') !== '1') {
            $this->markTestSkipped('This test makes sense only when run directly via specific browser driver');
        }

        $this->driver = RemoteWebDriver::create($this->serverUrl);

        $this->assertInstanceOf(RemoteWebDriver::class, $this->driver);
        $this->assertNotEmpty($this->driver->getSessionID());
    }

    public function testShouldCreateInstanceFromExistingSessionId()
    {
        // Create driver instance and load page "index.html"
        $originalDriver = RemoteWebDriver::create(
            $this->serverUrl,
            $this->desiredCapabilities,
            $this->connectionTimeout,
            $this->requestTimeout
        );
        $originalDriver->get($this->getTestPageUrl('index.html'));
        $this->compatAssertStringContainsString('/index.html', $originalDriver->getCurrentURL());

        // Store session ID
        $sessionId = $originalDriver->getSessionID();
        $isW3cCompliant = $originalDriver->isW3cCompliant();

        // Create new RemoteWebDriver instance based on the session ID
        $this->driver = RemoteWebDriver::createBySessionID($sessionId, $this->serverUrl, null, null, $isW3cCompliant);

        // Check we reused the previous instance (window) and it has the same URL
        $this->compatAssertStringContainsString('/index.html', $this->driver->getCurrentURL());

        // Do some interaction with the new driver
        $this->assertNotEmpty($this->driver->findElement(WebDriverBy::id('id_test'))->getText());
    }
}
