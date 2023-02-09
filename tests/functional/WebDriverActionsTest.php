<?php declare(strict_types=1);

namespace Facebook\WebDriver;

use Facebook\WebDriver\Remote\WebDriverBrowserType;

/**
 * @covers \Facebook\WebDriver\Interactions\Internal\WebDriverButtonReleaseAction
 * @covers \Facebook\WebDriver\Interactions\Internal\WebDriverClickAction
 * @covers \Facebook\WebDriver\Interactions\Internal\WebDriverClickAndHoldAction
 * @covers \Facebook\WebDriver\Interactions\Internal\WebDriverContextClickAction
 * @covers \Facebook\WebDriver\Interactions\Internal\WebDriverCoordinates
 * @covers \Facebook\WebDriver\Interactions\Internal\WebDriverDoubleClickAction
 * @covers \Facebook\WebDriver\Interactions\Internal\WebDriverKeyDownAction
 * @covers \Facebook\WebDriver\Interactions\Internal\WebDriverKeysRelatedAction
 * @covers \Facebook\WebDriver\Interactions\Internal\WebDriverKeyUpAction
 * @covers \Facebook\WebDriver\Interactions\Internal\WebDriverMouseAction
 * @covers \Facebook\WebDriver\Interactions\Internal\WebDriverMouseMoveAction
 * @covers \Facebook\WebDriver\Interactions\Internal\WebDriverMoveToOffsetAction
 * @covers \Facebook\WebDriver\Interactions\Internal\WebDriverSendKeysAction
 * @covers \Facebook\WebDriver\Interactions\Internal\WebDriverSingleKeyAction
 * @covers \Facebook\WebDriver\Interactions\WebDriverActions
 */
class WebDriverActionsTest extends WebDriverTestCase
{
    use RetrieveEventsTrait;

    public function testShouldClickOnElement(): void
    {
        $this->driver->get($this->getTestPageUrl(TestPage::EVENTS));

        $element = $this->driver->findElement(WebDriverBy::id('item-1'));

        $this->driver->action()
            ->click($element)
            ->perform();

        $logs = ['mouseover item-1', 'mousedown item-1', 'mouseup item-1', 'click item-1'];
        $loggedEvents = $this->retrieveLoggedMouseEvents();

        if (getenv('GECKODRIVER') === '1') {
            $loggedEvents = array_slice($loggedEvents, 0, count($logs));
            // Firefox sometimes triggers some extra events
            // it's not related to Geckodriver, it's Firefox's own behavior
        }

        $this->assertSame($logs, $loggedEvents);
    }

    public function testShouldClickAndHoldOnElementAndRelease(): void
    {
        $this->driver->get($this->getTestPageUrl(TestPage::EVENTS));

        $element = $this->driver->findElement(WebDriverBy::id('item-1'));

        $this->driver->action()
            ->clickAndHold($element)
            ->release()
            ->perform();

        if (self::isW3cProtocolBuild()) {
            $this->assertContains('mouseover item-1', $this->retrieveLoggedMouseEvents());
            $this->assertContains('mousedown item-1', $this->retrieveLoggedMouseEvents());
        } else {
            $this->assertSame(
                ['mouseover item-1', 'mousedown item-1', 'mouseup item-1', 'click item-1'],
                $this->retrieveLoggedMouseEvents()
            );
        }
    }

    /**
     * @group exclude-saucelabs
     */
    public function testShouldContextClickOnElement(): void
    {
        if ($this->desiredCapabilities->getBrowserName() === WebDriverBrowserType::MICROSOFT_EDGE) {
            $this->markTestSkipped('Getting stuck in EdgeDriver');
        }

        $this->driver->get($this->getTestPageUrl(TestPage::EVENTS));

        $element = $this->driver->findElement(WebDriverBy::id('item-2'));

        $this->driver->action()
            ->contextClick($element)
            ->perform();

        $loggedEvents = $this->retrieveLoggedMouseEvents();

        $this->assertContains('mousedown item-2', $loggedEvents);
        $this->assertContains('mouseup item-2', $loggedEvents);
        $this->assertContains('contextmenu item-2', $loggedEvents);
    }

    /**
     * @group exclude-safari
     *        https://github.com/webdriverio/webdriverio/issues/231
     */
    public function testShouldDoubleClickOnElement(): void
    {
        $this->driver->get($this->getTestPageUrl(TestPage::EVENTS));

        $element = $this->driver->findElement(WebDriverBy::id('item-3'));

        $this->driver->action()
            ->doubleClick($element)
            ->perform();

        $this->assertContains('dblclick item-3', $this->retrieveLoggedMouseEvents());
    }

    /**
     * @group exclude-saucelabs
     */
    public function testShouldSendKeysUpAndDown(): void
    {
        $this->driver->get($this->getTestPageUrl(TestPage::EVENTS));

        $this->driver->action()
            ->keyDown(null, WebDriverKeys::CONTROL)
            ->keyUp(null, WebDriverKeys::CONTROL)
            ->sendKeys(null, 'ab')
            ->perform();

        $events = $this->retrieveLoggedKeyboardEvents();

        $this->assertEquals(
            [
                'keydown "Control"',
                'keyup "Control"',
                'keydown "a"',
                'keyup "a"',
                'keydown "b"',
                'keyup "b"',
            ],
            $events
        );
    }

    /**
     * @group exclude-safari
     *        https://developer.apple.com/forums/thread/662677
     */
    public function testShouldMoveToElement(): void
    {
        $this->driver->get($this->getTestPageUrl(TestPage::SORTABLE));

        $item13 = $this->driver->findElement(WebDriverBy::id('item-1-3'));
        $item24 = $this->driver->findElement(WebDriverBy::id('item-2-4'));

        $this->driver->action()
            ->clickAndHold($item13)
            ->moveToElement($item24)
            ->release()
            ->perform();

        $this->assertSame(
            [['1-1', '1-2', '1-4', '1-5'], ['2-1', '2-2', '2-3', '2-4', '1-3', '2-5']],
            $this->retrieveListContent()
        );
    }

    /**
     * @group exclude-safari
     *        https://developer.apple.com/forums/thread/662677
     */
    public function testShouldMoveByOffset(): void
    {
        $this->driver->get($this->getTestPageUrl(TestPage::SORTABLE));

        $item13 = $this->driver->findElement(WebDriverBy::id('item-1-3'));

        $this->driver->action()
            ->clickAndHold($item13)
            ->moveByOffset(100, 55)
            ->release()
            ->perform();

        $this->assertSame(
            [['1-1', '1-2', '1-4', '1-5'], ['2-1', '2-2', '2-3', '2-4', '1-3', '2-5']],
            $this->retrieveListContent()
        );
    }

    /**
     * @group exclude-safari
     *        https://developer.apple.com/forums/thread/662677
     * @group exclude-saucelabs
     */
    public function testShouldDragAndDrop(): void
    {
        $this->driver->get($this->getTestPageUrl(TestPage::SORTABLE));

        $item13 = $this->driver->findElement(WebDriverBy::id('item-1-3'));
        $item24 = $this->driver->findElement(WebDriverBy::id('item-2-4'));

        $this->driver->action()
            ->dragAndDrop($item13, $item24)
            ->perform();

        $this->assertSame(
            [['1-1', '1-2', '1-4', '1-5'], ['2-1', '2-2', '2-3', '2-4', '1-3', '2-5']],
            $this->retrieveListContent()
        );

        $item21 = $this->driver->findElement(WebDriverBy::id('item-2-1'));

        $this->driver->action()
            ->dragAndDrop($item24, $item21)
            ->perform();

        $this->assertSame(
            [['1-1', '1-2', '1-4', '1-5'], ['2-4', '2-1', '2-2', '2-3', '1-3', '2-5']],
            $this->retrieveListContent()
        );
    }

    /**
     * @group exclude-safari
     *        https://developer.apple.com/forums/thread/662677
     *        it does not work even with Python Selenium, looks like Safaridriver does not implements Interaction API
     */
    public function testShouldDragAndDropBy(): void
    {
        $this->driver->get($this->getTestPageUrl(TestPage::SORTABLE));

        $item13 = $this->driver->findElement(WebDriverBy::id('item-1-3'));

        $this->driver->action()
            ->dragAndDropBy($item13, 100, 55)
            ->perform();

        $this->assertSame(
            [['1-1', '1-2', '1-4', '1-5'], ['2-1', '2-2', '2-3', '2-4', '1-3', '2-5']],
            $this->retrieveListContent()
        );

        $item25 = $this->driver->findElement(WebDriverBy::id('item-2-5'));
        $item22 = $this->driver->findElement(WebDriverBy::id('item-2-2'));

        $this->driver->action()
            ->dragAndDropBy($item25, 0, -130)
            ->dragAndDropBy($item22, -100, -35)
            ->perform();

        $this->assertSame(
            [['1-1', '2-2', '1-2', '1-4', '1-5'], ['2-1', '2-5', '2-3', '2-4', '1-3']],
            $this->retrieveListContent()
        );
    }

    private function retrieveListContent(): array
    {
        return [
            $this->retrieveLoggerEvents(WebDriverBy::cssSelector('#sortable1')),
            $this->retrieveLoggerEvents(WebDriverBy::cssSelector('#sortable2')),
        ];
    }
}
