<?php declare(strict_types=1);

namespace Facebook\WebDriver;

use Facebook\WebDriver\Exception\Internal\LogicException;
use Facebook\WebDriver\Remote\RemoteWebElement;

/**
 * @covers \Facebook\WebDriver\Remote\RemoteTargetLocator
 */
class RemoteTargetLocatorTest extends WebDriverTestCase
{
    public function testShouldSwitchToWindow(): void
    {
        $this->driver->get($this->getTestPageUrl(TestPage::OPEN_NEW_WINDOW));
        $originalWindowHandle = $this->driver->getWindowHandle();
        $windowHandlesBefore = $this->driver->getWindowHandles();

        $this->driver->findElement(WebDriverBy::cssSelector('a#open-new-window'))
            ->click();

        $this->driver->wait()->until(
            WebDriverExpectedCondition::numberOfWindowsToBe(2)
        );

        // At first the window should not be switched
        $this->assertStringContainsString('open_new_window.html', $this->driver->getCurrentURL());
        $this->assertSame($originalWindowHandle, $this->driver->getWindowHandle());

        /**
         * @see https://w3c.github.io/webdriver/#get-window-handles
         * > "The order in which the window handles are returned is arbitrary."
         * Thus we must first find out which window handle is the new one
         */
        $windowHandlesAfter = $this->driver->getWindowHandles();
        $newWindowHandle = array_diff($windowHandlesAfter, $windowHandlesBefore);
        $newWindowHandle = reset($newWindowHandle);

        $this->driver->switchTo()->window($newWindowHandle);

        $this->driver->wait()->until(function () {
            // The window contents is sometimes not yet loaded and needs a while to actually show the index.html page
            return mb_strpos($this->driver->getCurrentURL(), 'index.html') !== false;
        });

        // After switchTo() is called, the active window should be changed
        $this->assertStringContainsString('index.html', $this->driver->getCurrentURL());
        $this->assertNotSame($originalWindowHandle, $this->driver->getWindowHandle());
    }

    public function testActiveElement(): void
    {
        $this->driver->get($this->getTestPageUrl(TestPage::INDEX));

        $activeElement = $this->driver->switchTo()->activeElement();
        $this->assertInstanceOf(RemoteWebElement::class, $activeElement);
        $this->assertSame('body', $activeElement->getTagName());

        $this->driver->findElement(WebDriverBy::name('test_name'))->click();
        $activeElement = $this->driver->switchTo()->activeElement();
        $this->assertSame('input', $activeElement->getTagName());
        $this->assertSame('test_name', $activeElement->getAttribute('name'));
    }

    public function testShouldSwitchToFrameByItsId(): void
    {
        $parentPage = 'This is the host page which contains an iFrame';
        $firstChildFrame = 'This is the content of the iFrame';
        $secondChildFrame = 'open new window';

        $this->driver->get($this->getTestPageUrl(TestPage::PAGE_WITH_FRAME));

        $this->assertStringContainsString($parentPage, $this->driver->getPageSource());

        $this->driver->switchTo()->frame(0);
        $this->assertStringContainsString($firstChildFrame, $this->driver->getPageSource());

        $this->driver->switchTo()->frame(null);
        $this->assertStringContainsString($parentPage, $this->driver->getPageSource());

        $this->driver->switchTo()->frame(1);
        $this->assertStringContainsString($secondChildFrame, $this->driver->getPageSource());

        $this->driver->switchTo()->frame(null);
        $this->assertStringContainsString($parentPage, $this->driver->getPageSource());

        $this->driver->switchTo()->frame(0);
        $this->assertStringContainsString($firstChildFrame, $this->driver->getPageSource());

        $this->driver->switchTo()->defaultContent();
        $this->assertStringContainsString($parentPage, $this->driver->getPageSource());
    }

    public function testShouldSwitchToParentFrame(): void
    {
        $parentPage = 'This is the host page which contains an iFrame';
        $firstChildFrame = 'This is the content of the iFrame';

        $this->driver->get($this->getTestPageUrl(TestPage::PAGE_WITH_FRAME));

        $this->assertStringContainsString($parentPage, $this->driver->getPageSource());

        $this->driver->switchTo()->frame(0);
        $this->assertStringContainsString($firstChildFrame, $this->driver->getPageSource());

        $this->driver->switchTo()->parent();
        $this->assertStringContainsString($parentPage, $this->driver->getPageSource());
    }

    public function testShouldSwitchToFrameByElement(): void
    {
        $this->driver->get($this->getTestPageUrl(TestPage::PAGE_WITH_FRAME));

        $element = $this->driver->findElement(WebDriverBy::id('iframe_content'));
        $this->driver->switchTo()->frame($element);

        $this->assertStringContainsString('This is the content of the iFrame', $this->driver->getPageSource());
    }

    /**
     * @group exclude-saucelabs
     */
    public function testShouldCreateNewWindow(): void
    {
        self::skipForJsonWireProtocol('Create new window is not supported in JsonWire protocol');

        // Ensure that the initial context matches.
        $initialHandle = $this->driver->getWindowHandle();
        $this->driver->get($this->getTestPageUrl(TestPage::INDEX));
        $this->assertEquals($this->getTestPageUrl(TestPage::INDEX), $this->driver->getCurrentUrl());
        $source = $this->driver->getPageSource();
        $this->assertStringContainsString('<h1 id="welcome">', $source);
        $this->assertStringContainsString('Welcome to the php-webdriver testing page.', $source);
        $windowHandles = $this->driver->getWindowHandles();
        $this->assertCount(1, $windowHandles);

        // Create a new window
        $this->driver->switchTo()->newWindow();

        $windowHandles = $this->driver->getWindowHandles();
        $this->assertCount(2, $windowHandles);

        $newWindowHandle = $this->driver->getWindowHandle();
        $this->driver->get($this->getTestPageUrl(TestPage::UPLOAD));
        $this->assertEquals($this->getTestPageUrl(TestPage::UPLOAD), $this->driver->getCurrentUrl());
        $this->assertNotEquals($initialHandle, $newWindowHandle);

        // Switch back to original context.
        $this->driver->switchTo()->window($initialHandle);
        $this->assertEquals($this->getTestPageUrl(TestPage::INDEX), $this->driver->getCurrentUrl());
    }

    /**
     * @group exclude-saucelabs
     */
    public function testShouldNotAcceptStringAsFrameIdInW3cMode(): void
    {
        self::skipForJsonWireProtocol();

        $this->driver->get($this->getTestPageUrl(TestPage::PAGE_WITH_FRAME));

        $this->expectException(LogicException::class);
        $this->expectExceptionMessage(
            'In W3C compliance mode frame must be either instance of WebDriverElement, integer or null'
        );

        $this->driver->switchTo()->frame('iframe_content');
    }

    /**
     * @group exclude-saucelabs
     */
    public function testShouldAcceptStringAsFrameIdInJsonWireMode(): void
    {
        self::skipForW3cProtocol();

        $this->driver->get($this->getTestPageUrl(TestPage::PAGE_WITH_FRAME));

        $this->driver->switchTo()->frame('iframe_content');

        $this->assertStringContainsString('This is the content of the iFrame', $this->driver->getPageSource());
    }
}
