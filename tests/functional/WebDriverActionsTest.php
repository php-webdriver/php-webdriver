<?php

namespace Facebook\WebDriver;

use Facebook\WebDriver\Remote\WebDriverBrowserType;

/**
 * @coversDefaultClass \Facebook\WebDriver\Interactions\WebDriverActions
 */
class WebDriverActionsTest extends WebDriverTestCase
{
    protected function setUp()
    {
        parent::setUp();

        $this->driver->get($this->getTestPageUrl('events.html'));
    }

    /**
     * @covers ::__construct
     * @covers ::click
     * @covers ::perform
     */
    public function testShouldClickOnElement()
    {
        if ($this->desiredCapabilities->getBrowserName() === WebDriverBrowserType::HTMLUNIT) {
            $this->markTestSkipped('Not supported by HtmlUnit browser');
        }

        $element = $this->driver->findElement(WebDriverBy::id('item-1'));

        $this->driver->action()
            ->click($element)
            ->perform();

        $logs = ['mouseover item-1', 'mousedown item-1', 'mouseup item-1', 'click item-1'];
        $loggedEvents = $this->retrieveLoggedEvents();

        if (getenv('GECKODRIVER') === '1') {
            $loggedEvents = array_slice($loggedEvents, 0, count($logs));
            // Firefox sometimes triggers some extra events
            // it's not related to Geckodriver, it's Firefox's own behavior
        }

        $this->assertSame($logs, $loggedEvents);
    }

    /**
     * @covers ::__construct
     * @covers ::clickAndHold
     * @covers ::perform
     * @covers ::release
     */
    public function testShouldClickAndHoldOnElementAndRelease()
    {
        if ($this->desiredCapabilities->getBrowserName() === WebDriverBrowserType::HTMLUNIT) {
            $this->markTestSkipped('Not supported by HtmlUnit browser');
        }

        $element = $this->driver->findElement(WebDriverBy::id('item-1'));

        $this->driver->action()
            ->clickAndHold($element)
            ->release()
            ->perform();

        if (self::isW3cProtocolBuild()) {
            $this->assertArraySubset(['mouseover item-1', 'mousedown item-1'], $this->retrieveLoggedEvents());
        } else {
            $this->assertSame(
                ['mouseover item-1', 'mousedown item-1', 'mouseup item-1', 'click item-1'],
                $this->retrieveLoggedEvents()
            );
        }
    }

    /**
     * @group exclude-saucelabs
     * @covers ::__construct
     * @covers ::contextClick
     * @covers ::perform
     */
    public function testShouldContextClickOnElement()
    {
        if ($this->desiredCapabilities->getBrowserName() === WebDriverBrowserType::HTMLUNIT) {
            $this->markTestSkipped('Not supported by HtmlUnit browser');
        }

        if ($this->desiredCapabilities->getBrowserName() === WebDriverBrowserType::MICROSOFT_EDGE) {
            $this->markTestSkipped('Getting stuck in EdgeDriver');
        }

        $element = $this->driver->findElement(WebDriverBy::id('item-2'));

        $this->driver->action()
            ->contextClick($element)
            ->perform();

        $loggedEvents = $this->retrieveLoggedEvents();

        $this->assertContains('mousedown item-2', $loggedEvents);
        $this->assertContains('mouseup item-2', $loggedEvents);
        $this->assertContains('contextmenu item-2', $loggedEvents);
    }

    /**
     * @covers ::__construct
     * @covers ::doubleClick
     * @covers ::perform
     */
    public function testShouldDoubleClickOnElement()
    {
        if ($this->desiredCapabilities->getBrowserName() === WebDriverBrowserType::HTMLUNIT) {
            $this->markTestSkipped('Not supported by HtmlUnit browser');
        }

        $element = $this->driver->findElement(WebDriverBy::id('item-3'));

        $this->driver->action()
            ->doubleClick($element)
            ->perform();

        $this->assertContains('dblclick item-3', $this->retrieveLoggedEvents());
    }

    /**
     * @covers ::__construct
     * @covers ::dragAndDrop
     * @covers ::perform
     * @group exclude-saucelabs
     */
    public function testShouldDragAndDrop()
    {
        if ($this->desiredCapabilities->getBrowserName() === WebDriverBrowserType::HTMLUNIT) {
            $this->markTestSkipped('Not supported by HtmlUnit browser');
        }

        $element = $this->driver->findElement(WebDriverBy::id('item-3'));
        $target = $this->driver->findElement(WebDriverBy::id('item-1'));

        $this->driver->action()
            ->dragAndDrop($element, $target)
            ->perform();

        $this->assertContains('mouseover item-3', $this->retrieveLoggedEvents());
        $this->assertContains('mousedown item-3', $this->retrieveLoggedEvents());
        $this->assertContains('mouseover item-1', $this->retrieveLoggedEvents());
        $this->assertContains('mouseup item-1', $this->retrieveLoggedEvents());
    }

    /**
     * @return array
     */
    private function retrieveLoggedEvents()
    {
        $logElement = $this->driver->findElement(WebDriverBy::id('mouseEventsLog'));

        return explode("\n", $logElement->getText());
    }
}
