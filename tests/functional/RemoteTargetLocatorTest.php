<?php

namespace Facebook\WebDriver;

use Facebook\WebDriver\Remote\RemoteWebElement;

/**
 * @covers \Facebook\WebDriver\Remote\RemoteTargetLocator
 */
class RemoteTargetLocatorTest extends WebDriverTestCase
{
    public function testShouldSwitchToWindow()
    {
        $this->driver->get($this->getTestPageUrl('open_new_window.html'));
        $originalWindowHandle = $this->driver->getWindowHandle();
        $windowHandlesBefore = $this->driver->getWindowHandles();

        $this->driver->findElement(WebDriverBy::cssSelector('a#open-new-window'))
            ->click();

        $this->driver->wait()->until(
            WebDriverExpectedCondition::numberOfWindowsToBe(2)
        );

        // At first the window should not be switched
        $this->compatAssertStringContainsString('open_new_window.html', $this->driver->getCurrentURL());
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

        // After switchTo() is called, the active window should be changed
        $this->compatAssertStringContainsString('index.html', $this->driver->getCurrentURL());
        $this->assertNotSame($originalWindowHandle, $this->driver->getWindowHandle());
    }

    public function testActiveElement()
    {
        $this->driver->get($this->getTestPageUrl('index.html'));

        $activeElement = $this->driver->switchTo()->activeElement();
        $this->assertInstanceOf(RemoteWebElement::class, $activeElement);
        $this->assertSame('body', $activeElement->getTagName());

        $this->driver->findElement(WebDriverBy::name('test_name'))->click();
        $activeElement = $this->driver->switchTo()->activeElement();
        $this->assertSame('input', $activeElement->getTagName());
        $this->assertSame('test_name', $activeElement->getAttribute('name'));
    }

    public function testShouldSwitchToFrameByItsId()
    {
        $parentPage = 'This is the host page which contains an iFrame';
        $firstChildFrame = 'This is the content of the iFrame';
        $secondChildFrame = 'open new window';

        $this->driver->get($this->getTestPageUrl('page_with_frame.html'));

        $this->compatAssertStringContainsString($parentPage, $this->driver->getPageSource());

        $this->driver->switchTo()->frame(0);
        $this->compatAssertStringContainsString($firstChildFrame, $this->driver->getPageSource());

        $this->driver->switchTo()->frame(null);
        $this->compatAssertStringContainsString($parentPage, $this->driver->getPageSource());

        $this->driver->switchTo()->frame(1);
        $this->compatAssertStringContainsString($secondChildFrame, $this->driver->getPageSource());

        $this->driver->switchTo()->frame(null);
        $this->compatAssertStringContainsString($parentPage, $this->driver->getPageSource());

        $this->driver->switchTo()->frame(0);
        $this->compatAssertStringContainsString($firstChildFrame, $this->driver->getPageSource());

        $this->driver->switchTo()->defaultContent();
        $this->compatAssertStringContainsString($parentPage, $this->driver->getPageSource());
    }

    public function testShouldSwitchToParentFrame()
    {
        $parentPage = 'This is the host page which contains an iFrame';
        $firstChildFrame = 'This is the content of the iFrame';

        $this->driver->get($this->getTestPageUrl('page_with_frame.html'));

        $this->compatAssertStringContainsString($parentPage, $this->driver->getPageSource());

        $this->driver->switchTo()->frame(0);
        $this->compatAssertStringContainsString($firstChildFrame, $this->driver->getPageSource());

        $this->driver->switchTo()->parent();
        $this->compatAssertStringContainsString($parentPage, $this->driver->getPageSource());
    }

    public function testShouldSwitchToFrameByElement()
    {
        $this->driver->get($this->getTestPageUrl('page_with_frame.html'));

        $element = $this->driver->findElement(WebDriverBy::id('iframe_content'));
        $this->driver->switchTo()->frame($element);

        $this->compatAssertStringContainsString('This is the content of the iFrame', $this->driver->getPageSource());
    }

    /**
     * @group exclude-saucelabs
     * @covers ::window
     */
    public function testShouldCreateNewWindow()
    {
        self::skipForJsonWireProtocol('Create new window is not supported in JsonWire protocol');

        // Ensure that the initial context matches.
        $initialHandle = $this->driver->getWindowHandle();
        $this->driver->get($this->getTestPageUrl('index.html'));
        $this->assertEquals($this->getTestPageUrl('index.html'), $this->driver->getCurrentUrl());
        $source = $this->driver->getPageSource();
        $this->compatAssertStringContainsString('<h1 id="welcome">', $source);
        $this->compatAssertStringContainsString('Welcome to the php-webdriver testing page.', $source);
        $windowHandles = $this->driver->getWindowHandles();
        $this->assertCount(1, $windowHandles);

        // Create a new window
        $this->driver->switchTo()->newWindow();

        $windowHandles = $this->driver->getWindowHandles();
        $this->assertCount(2, $windowHandles);

        $newWindowHandle = $this->driver->getWindowHandle();
        $this->driver->get($this->getTestPageUrl('upload.html'));
        $this->assertEquals($this->getTestPageUrl('upload.html'), $this->driver->getCurrentUrl());
        $this->assertNotEquals($initialHandle, $newWindowHandle);

        // Switch back to original context.
        $this->driver->switchTo()->window($initialHandle);
        $this->assertEquals($this->getTestPageUrl('index.html'), $this->driver->getCurrentUrl());
    }

    /**
     * @group exclude-saucelabs
     */
    public function testShouldNotAcceptStringAsFrameIdInW3cMode()
    {
        self::skipForJsonWireProtocol();

        $this->driver->get($this->getTestPageUrl('page_with_frame.html'));

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage(
            'In W3C compliance mode frame must be either instance of WebDriverElement, integer or null'
        );

        $this->driver->switchTo()->frame('iframe_content');
    }

    /**
     * @group exclude-saucelabs
     */
    public function testShouldAcceptStringAsFrameIdInJsonWireMode()
    {
        self::skipForW3cProtocol();

        $this->driver->get($this->getTestPageUrl('page_with_frame.html'));

        $this->driver->switchTo()->frame('iframe_content');

        $this->compatAssertStringContainsString('This is the content of the iFrame', $this->driver->getPageSource());
    }
}
