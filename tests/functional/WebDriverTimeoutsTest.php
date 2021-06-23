<?php

namespace PhpWebDriver\WebDriver;

use PhpWebDriver\WebDriver\Exception\NoSuchElementException;
use PhpWebDriver\WebDriver\Exception\ScriptTimeoutException;
use PhpWebDriver\WebDriver\Exception\TimeoutException;
use PhpWebDriver\WebDriver\Remote\RemoteWebElement;

/**
 * @coversDefaultClass \PhpWebDriver\WebDriver\WebDriverTimeouts
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
        $this->driver->manage()->timeouts()->pageLoadTimeout(1);

        try {
            $this->driver->get($this->getTestPageUrl('slow_loading.html'));
            $this->fail('ScriptTimeoutException or TimeoutException exception should be thrown');
        } catch (TimeoutException $e) { // thrown by Selenium 3.0.0+
        } catch (ScriptTimeoutException $e) { // thrown by Selenium 2
        }

        $this->assertTrue(true); // To generate coverage, see https://github.com/sebastianbergmann/phpunit/issues/3016
    }
}
