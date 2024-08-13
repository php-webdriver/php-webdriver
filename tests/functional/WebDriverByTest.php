<?php declare(strict_types=1);

namespace Facebook\WebDriver;

use Facebook\WebDriver\Remote\RemoteWebElement;

/**
 * Tests for locator strategies provided by WebDriverBy.
 * @covers \Facebook\WebDriver\WebDriverBy
 */
class WebDriverByTest extends WebDriverTestCase
{
    /**
     * @dataProvider provideTextElements
     */
    public function testShouldFindTextElementByLocator(
        string $webDriverByLocatorMethod,
        string $webDriverByLocatorValue,
        ?string $expectedText = null,
        ?string $expectedAttributeValue = null
    ): void {
        $this->driver->get($this->getTestPageUrl(TestPage::INDEX));

        $by = call_user_func([WebDriverBy::class, $webDriverByLocatorMethod], $webDriverByLocatorValue);
        $element = $this->driver->findElement($by);

        $this->assertInstanceOf(RemoteWebElement::class, $element);

        if ($expectedText !== null) {
            $this->assertEquals($expectedText, $element->getText());
        }

        if ($expectedAttributeValue !== null) {
            $this->assertEquals($expectedAttributeValue, $element->getAttribute('value'));
        }
    }

    /**
     * @return array[]
     */
    public function provideTextElements(): array
    {
        return [
            'id' => ['id', 'id_test', 'Test by ID'],
            'className' => ['className', 'test_class', 'Test by Class'],
            'cssSelector' => ['cssSelector', '.test_class', 'Test by Class'],
            'linkText' => ['linkText', 'Click here', 'Click here'],
            'partialLinkText' => ['partialLinkText', 'Click', 'Click here'],
            'xpath' => ['xpath', '//input[@name="test_name"]', '', 'Test Value'],
            'name' => ['name', 'test_name', '', 'Test Value'],
            'tagName' => ['tagName', 'input', '', 'Test Value'],
        ];
    }
}
