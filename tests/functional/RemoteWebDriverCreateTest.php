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

        $this->assertInternalType('string', $this->driver->getSessionID());
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
        $this->assertContains('/index.html', $originalDriver->getCurrentURL());

        // Store session ID
        $sessionId = $originalDriver->getSessionID();

        // Create new RemoteWebDriver instance based on the session ID
        $this->driver = RemoteWebDriver::createBySessionID($sessionId, $this->serverUrl);

        // Check we reused the previous instance (window) and it has the same URL
        $this->assertContains('/index.html', $this->driver->getCurrentURL());
    }
}
