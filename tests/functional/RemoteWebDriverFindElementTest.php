<?php declare(strict_types=1);

namespace Facebook\WebDriver;

use Facebook\WebDriver\Exception\NoSuchElementException;
use Facebook\WebDriver\Remote\RemoteWebElement;

/**
 * Tests for findElement() and findElements() method of RemoteWebDriver.
 * @covers \Facebook\WebDriver\Remote\RemoteWebDriver
 */
class RemoteWebDriverFindElementTest extends WebDriverTestCase
{
    public function testShouldThrowExceptionIfElementCannotBeFound(): void
    {
        $this->driver->get($this->getTestPageUrl(TestPage::INDEX));

        $this->expectException(NoSuchElementException::class);
        $this->driver->findElement(WebDriverBy::id('not_existing'));
    }

    public function testShouldFindElementIfExistsOnAPage(): void
    {
        $this->driver->get($this->getTestPageUrl(TestPage::INDEX));

        $element = $this->driver->findElement(WebDriverBy::id('id_test'));

        $this->assertInstanceOf(RemoteWebElement::class, $element);
    }

    public function testShouldReturnEmptyArrayIfElementsCannotBeFound(): void
    {
        $this->driver->get($this->getTestPageUrl(TestPage::INDEX));

        $elements = $this->driver->findElements(WebDriverBy::cssSelector('not_existing'));

        $this->assertIsArray($elements);
        $this->assertCount(0, $elements);
    }

    public function testShouldFindMultipleElements(): void
    {
        $this->driver->get($this->getTestPageUrl(TestPage::INDEX));

        $elements = $this->driver->findElements(WebDriverBy::cssSelector('ul > li'));

        $this->assertIsArray($elements);
        $this->assertCount(5, $elements);
        $this->assertContainsOnlyInstancesOf(RemoteWebElement::class, $elements);
    }

    /**
     * @group exclude-saucelabs
     */
    public function testEscapeCssSelector(): void
    {
        self::skipForJsonWireProtocol(
            'CSS selectors containing special characters are not supported by the legacy protocol'
        );

        $this->driver->get($this->getTestPageUrl(TestPage::ESCAPE_CSS));

        $element = $this->driver->findElement(WebDriverBy::id('.fo\'oo'));
        $this->assertSame('Foo', $element->getText());

        $element = $this->driver->findElement(WebDriverBy::className('#ba\'r'));
        $this->assertSame('Bar', $element->getText());

        $element = $this->driver->findElement(WebDriverBy::name('.#ba\'z'));
        $this->assertSame('Baz', $element->getText());
    }
}
