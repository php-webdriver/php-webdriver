<?php

namespace Facebook\WebDriver;

/**
 * @coversDefaultClass \Facebook\WebDriver\WebDriverNavigation
 */
class WebDriverNavigationTest extends WebDriverTestCase
{
    /**
     * @covers ::__construct
     * @covers ::to
     */
    public function testShouldNavigateToUrl()
    {
        $this->driver->navigate()->to($this->getTestPageUrl('index.html'));

        $this->assertStringEndsWith('/index.html', $this->driver->getCurrentURL());
    }

    /**
     * @covers ::back
     * @covers ::forward
     */
    public function testShouldNavigateBackAndForward()
    {
        $this->driver->get($this->getTestPageUrl('index.html'));
        $linkElement = $this->driver->findElement(WebDriverBy::id('a-form'));

        $linkElement->click();

        $this->driver->wait()->until(
            WebDriverExpectedCondition::urlContains('form.html')
        );

        $this->driver->navigate()->back();

        $this->driver->wait()->until(
            WebDriverExpectedCondition::urlContains('index.html')
        );

        $this->driver->navigate()->forward();

        $this->driver->wait()->until(
            WebDriverExpectedCondition::urlContains('form.html')
        );
    }

    /**
     * @covers ::refresh
     */
    public function testShouldRefreshPage()
    {
        $this->driver->get($this->getTestPageUrl('index.html'));

        // Change input element content, to make sure it was refreshed (=> cleared to original value)
        $inputElement = $this->driver->findElement(WebDriverBy::name('test_name'));
        $inputElementOriginalValue = $inputElement->getAttribute('value');
        $inputElement->clear()->sendKeys('New value');
        $this->assertSame('New value', $inputElement->getAttribute('value'));

        $this->driver->navigate()->refresh();

        $this->driver->wait()->until(
            WebDriverExpectedCondition::stalenessOf($inputElement)
        );

        $inputElementAfterRefresh = $this->driver->findElement(WebDriverBy::name('test_name'));

        $this->assertSame($inputElementOriginalValue, $inputElementAfterRefresh->getAttribute('value'));
    }
}
