<?php declare(strict_types=1);

namespace Facebook\WebDriver;

use Facebook\WebDriver\Exception\NoSuchShadowRootException;
use Facebook\WebDriver\Remote\ShadowRoot;

/**
 * @group exclude-safari
 *        Safaridriver does not support Shadow DOM endpoints
 * @covers \Facebook\WebDriver\Remote\RemoteWebElement
 * @covers \Facebook\WebDriver\Remote\ShadowRoot
 */
class ShadowDomTest extends WebDriverTestCase
{
    protected function setUp(): void
    {
        self::skipForJsonWireProtocol('Shadow DOM is only part of W3C WebDriver');

        parent::setUp();
    }

    public function testShouldGetShadowRoot(): void
    {
        $this->driver->get($this->getTestPageUrl(TestPage::WEB_COMPONENTS));

        $element = $this->driver->findElement(WebDriverBy::cssSelector('custom-checkbox-element'));

        $shadowRoot = $element->getShadowRoot();

        $this->assertInstanceOf(ShadowRoot::class, $shadowRoot);
    }

    public function testShouldThrowExceptionWhenGettingShadowRootWithElementNotHavingShadowRoot(): void
    {
        $this->driver->get($this->getTestPageUrl(TestPage::WEB_COMPONENTS));

        $element = $this->driver->findElement(WebDriverBy::cssSelector('#no-shadow-root'));

        $this->expectException(NoSuchShadowRootException::class);
        $element->getShadowRoot();
    }

    public function testShouldFindElementUnderShadowRoot(): void
    {
        $this->driver->get($this->getTestPageUrl(TestPage::WEB_COMPONENTS));

        $element = $this->driver->findElement(WebDriverBy::cssSelector('custom-checkbox-element'));

        $shadowRoot = $element->getShadowRoot();

        $elementInShadow = $shadowRoot->findElement(WebDriverBy::cssSelector('input'));
        $this->assertSame('checkbox', $elementInShadow->getAttribute('type'));

        $elementsInShadow = $shadowRoot->findElements(WebDriverBy::cssSelector('div'));
        $this->assertCount(2, $elementsInShadow);
    }

    public function testShouldReferenceTheSameShadowRootAsFromExecuteScript(): void
    {
        $this->driver->get($this->getTestPageUrl(TestPage::WEB_COMPONENTS));

        $element = $this->driver->findElement(WebDriverBy::cssSelector('custom-checkbox-element'));

        /** @var WebDriverElement $elementFromScript */
        $elementFromScript = $this->driver->executeScript(
            'return arguments[0].shadowRoot;',
            [$element]
        );

        $shadowRoot = $element->getShadowRoot();

        $this->assertSame($shadowRoot->getId(), reset($elementFromScript));
    }
}
