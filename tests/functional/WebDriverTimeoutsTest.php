<?php declare(strict_types=1);

namespace Facebook\WebDriver;

use Facebook\WebDriver\Exception\NoSuchElementException;
use Facebook\WebDriver\Exception\ScriptTimeoutException;
use Facebook\WebDriver\Exception\TimeoutException;
use Facebook\WebDriver\Remote\RemoteWebElement;

/**
 * @coversDefaultClass \Facebook\WebDriver\WebDriverTimeouts
 */
class WebDriverTimeoutsTest extends WebDriverTestCase
{
    /**
     * @group exclude-saucelabs
     */
    public function testShouldFailGettingDelayedElementWithoutWait(): void
    {
        $this->driver->get($this->getTestPageUrl(TestPage::DELAYED_ELEMENT));

        $this->expectException(NoSuchElementException::class);
        $this->driver->findElement(WebDriverBy::id('delayed'));
    }

    /**
     * @covers ::__construct
     * @covers ::implicitlyWait
     */
    public function testShouldGetDelayedElementWithImplicitWait(): void
    {
        $this->driver->get($this->getTestPageUrl(TestPage::DELAYED_ELEMENT));

        $this->driver->manage()->timeouts()->implicitlyWait(2);
        $element = $this->driver->findElement(WebDriverBy::id('delayed'));

        $this->assertInstanceOf(RemoteWebElement::class, $element);
    }

    /**
     * @group exclude-saucelabs
     * @covers ::__construct
     * @covers ::pageLoadTimeout
     */
    public function testShouldFailIfPageIsLoadingLongerThanPageLoadTimeout(): void
    {
        $this->driver->manage()->timeouts()->pageLoadTimeout(1);

        try {
            $this->driver->get($this->getTestPageUrl(TestPage::SLOW_LOADING));
            $this->fail('ScriptTimeoutException or TimeoutException exception should be thrown');
        } catch (TimeoutException $e) { // thrown by Selenium 3.0.0+
        } catch (ScriptTimeoutException $e) { // thrown by Selenium 2
        }

        $this->assertTrue(true); // To generate coverage, see https://github.com/sebastianbergmann/phpunit/issues/3016
    }
}
