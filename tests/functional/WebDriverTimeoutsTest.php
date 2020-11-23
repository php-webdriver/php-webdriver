<?php

namespace Facebook\WebDriver;

use Facebook\WebDriver\Exception\NoSuchElementException;
use Facebook\WebDriver\Exception\ScriptTimeoutException;
use Facebook\WebDriver\Exception\TimeoutException;
use Facebook\WebDriver\Remote\RemoteWebElement;
use Facebook\WebDriver\Remote\WebDriverBrowserType;

/**
 * @coversDefaultClass \Facebook\WebDriver\WebDriverTimeouts
 */
class WebDriverTimeoutsTest extends WebDriverTestCase
{
    /**
     * @group exclude-saucelabs
     */
    public function testShouldFailGettingDelayedElementWithoutWait()
    {
        $this->driver->get($this->getTestPageUrl('delayed_element.html'));

        $this->expectException(NoSuchElementException::class);
        $this->driver->findElement(WebDriverBy::id('delayed'));
    }

    /**
     * @covers ::__construct
     * @covers ::implicitlyWait
     */
    public function testShouldGetDelayedElementWithImplicitWait()
    {
        $this->driver->get($this->getTestPageUrl('delayed_element.html'));

        $this->driver->manage()->timeouts()->implicitlyWait(2);
        $element = $this->driver->findElement(WebDriverBy::id('delayed'));

        $this->assertInstanceOf(RemoteWebElement::class, $element);
    }

    /**
     * @group exclude-saucelabs
     * @covers ::__construct
     * @covers ::pageLoadTimeout
     */
    public function testShouldFailIfPageIsLoadingLongerThanPageLoadTimeout()
    {
        if ($this->desiredCapabilities->getBrowserName() === WebDriverBrowserType::HTMLUNIT) {
            $this->markTestSkipped('Not supported by HtmlUnit browser');
        }

        $this->driver->manage()->timeouts()->pageLoadTimeout(1);

        try {
            $this->driver->get($this->getTestPageUrl('slow_loading.html'));
            $this->fail('ScriptTimeoutException or TimeoutException exception should be thrown');
        } catch (TimeoutException $e) { // thrown by Selenium 3.0.0+
        } catch (ScriptTimeoutException $e) { // thrown by Selenium 2
        }
    }
}
