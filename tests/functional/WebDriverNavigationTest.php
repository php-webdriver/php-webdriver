<?php declare(strict_types=1);

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
    public function testShouldNavigateToUrl(): void
    {
        $this->driver->navigate()->to($this->getTestPageUrl(TestPage::INDEX));

        $this->assertStringEndsWith('/index.html', $this->driver->getCurrentURL());
    }

    /**
     * @covers ::back
     * @covers ::forward
     */
    public function testShouldNavigateBackAndForward(): void
    {
        $this->driver->get($this->getTestPageUrl(TestPage::INDEX));
        $linkElement = $this->driver->findElement(WebDriverBy::id('a-form'));

        $linkElement->click();

        $this->driver->wait()->until(
            WebDriverExpectedCondition::urlContains(TestPage::FORM)
        );

        $this->driver->navigate()->back();

        $this->driver->wait()->until(
            WebDriverExpectedCondition::urlContains(TestPage::INDEX)
        );

        $this->driver->navigate()->forward();

        $this->driver->wait()->until(
            WebDriverExpectedCondition::urlContains(TestPage::FORM)
        );

        $this->assertTrue(true); // To generate coverage, see https://github.com/sebastianbergmann/phpunit/issues/3016
    }

    /**
     * @covers ::refresh
     */
    public function testShouldRefreshPage(): void
    {
        $this->driver->get($this->getTestPageUrl(TestPage::INDEX));

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
