<?php declare(strict_types=1);

namespace Facebook\WebDriver;

use Facebook\WebDriver\Exception\ElementNotInteractableException;
use Facebook\WebDriver\Exception\NoSuchElementException;
use Facebook\WebDriver\Remote\RemoteWebElement;
use OndraM\CiDetector\CiDetector;

/**
 * @coversDefaultClass \Facebook\WebDriver\Remote\RemoteWebElement
 */
class RemoteWebElementTest extends WebDriverTestCase
{
    /**
     * @covers ::getText
     * @group exclude-edge
     *        https://developer.microsoft.com/en-us/microsoft-edge/platform/issues/5569343/
     * @group exclude-safari
     *      Safari does not normalize white-spaces
     */
    public function testShouldGetText(): void
    {
        $this->driver->get($this->getTestPageUrl(TestPage::INDEX));
        $elementWithSimpleText = $this->driver->findElement(WebDriverBy::id('text-simple'));
        $elementWithTextWithSpaces = $this->driver->findElement(WebDriverBy::id('text-with-spaces'));

        $this->assertEquals('Foo bar text', $elementWithSimpleText->getText());

        $this->assertEquals('Multiple spaces are stripped', $elementWithTextWithSpaces->getText());
    }

    /**
     * @covers ::getAttribute
     */
    public function testShouldGetAttributeValue(): void
    {
        $this->driver->get($this->getTestPageUrl(TestPage::INDEX));

        $element = $this->driver->findElement(WebDriverBy::id('text-simple'));

        $this->assertSame('note', $element->getAttribute('role'));
        $this->assertSame('height: 5em; border: 1px solid black;', $element->getAttribute('style'));
        $this->assertSame('text-simple', $element->getAttribute('id'));
        $this->assertNull($element->getAttribute('notExisting'));
    }

    /**
     * @covers ::getDomProperty
     */
    public function testShouldGetDomPropertyValue(): void
    {
        self::skipForJsonWireProtocol();

        $this->driver->get($this->getTestPageUrl(TestPage::INDEX));

        $element = $this->driver->findElement(WebDriverBy::id('div-with-html'));

        $this->assertStringContainsString(
            ' <p>This <code>div</code> has some more <strong>html</strong> inside.</p>',
            $element->getDomProperty('innerHTML')
        );
        $this->assertSame('foo bar', $element->getDomProperty('className')); // IDL property
        $this->assertSame('foo bar', $element->getAttribute('class')); // HTML attribute should be the same
        $this->assertSame('DIV', $element->getDomProperty('tagName'));
        $this->assertSame(2, $element->getDomProperty('childElementCount'));
        $this->assertNull($element->getDomProperty('notExistingProperty'));
    }

    /**
     * @covers ::getLocation
     */
    public function testShouldGetLocation(): void
    {
        $this->driver->get($this->getTestPageUrl(TestPage::INDEX));

        $element = $this->driver->findElement(WebDriverBy::id('element-with-location'));

        $elementLocation = $element->getLocation();
        $this->assertInstanceOf(WebDriverPoint::class, $elementLocation);
        $this->assertSame(33, $elementLocation->getX());
        $this->assertSame(550, $elementLocation->getY());
    }

    /**
     * @covers ::getLocationOnScreenOnceScrolledIntoView
     */
    public function testShouldGetLocationOnScreenOnceScrolledIntoView(): void
    {
        $this->driver->get($this->getTestPageUrl(TestPage::INDEX));

        $element = $this->driver->findElement(WebDriverBy::id('element-out-of-viewport'));

        // Location before scrolling into view is out of viewport
        $elementLocation = $element->getLocation();
        $this->assertInstanceOf(WebDriverPoint::class, $elementLocation);
        $this->assertSame(33, $elementLocation->getX());
        $this->assertSame(5000, $elementLocation->getY());

        // Location once scrolled into view
        $elementLocationOnceScrolledIntoView = $element->getLocationOnScreenOnceScrolledIntoView();
        $this->assertInstanceOf(WebDriverPoint::class, $elementLocationOnceScrolledIntoView);
        $this->assertSame(33, $elementLocationOnceScrolledIntoView->getX());
        $this->assertLessThan(
            1000, // screen size is ~768, so this should be less
            $elementLocationOnceScrolledIntoView->getY()
        );
    }

    /**
     * @covers ::getSize
     */
    public function testShouldGetSize(): void
    {
        $this->driver->get($this->getTestPageUrl(TestPage::INDEX));

        $element = $this->driver->findElement(WebDriverBy::id('element-with-location'));

        $elementSize = $element->getSize();
        $this->assertInstanceOf(WebDriverDimension::class, $elementSize);
        $this->assertSame(333, $elementSize->getWidth());
        $this->assertSame(66, $elementSize->getHeight());
    }

    /**
     * @covers ::getCSSValue
     */
    public function testShouldGetCssValue(): void
    {
        $this->driver->get($this->getTestPageUrl(TestPage::INDEX));

        $elementWithBorder = $this->driver->findElement(WebDriverBy::id('text-simple'));
        $elementWithoutBorder = $this->driver->findElement(WebDriverBy::id('text-with-spaces'));

        $this->assertSame('solid', $elementWithBorder->getCSSValue('border-left-style'));
        $this->assertSame('none', $elementWithoutBorder->getCSSValue('border-left-style'));

        // Browser could report color in either rgb (like MS Edge) or rgba (like everyone else)
        $this->assertMatchesRegularExpression(
            '/rgba?\(0, 0, 0(, 1)?\)/',
            $elementWithBorder->getCSSValue('border-left-color')
        );
    }

    /**
     * @covers ::getTagName
     */
    public function testShouldGetTagName(): void
    {
        $this->driver->get($this->getTestPageUrl(TestPage::INDEX));

        $paragraphElement = $this->driver->findElement(WebDriverBy::id('id_test'));

        $this->assertSame('p', $paragraphElement->getTagName());
    }

    /**
     * @covers ::click
     */
    public function testShouldClick(): void
    {
        $this->driver->get($this->getTestPageUrl(TestPage::INDEX));
        $linkElement = $this->driver->findElement(WebDriverBy::id('a-form'));

        $linkElement->click();

        $this->driver->wait()->until(
            WebDriverExpectedCondition::urlContains(TestPage::FORM)
        );

        $this->assertTrue(true); // To generate coverage, see https://github.com/sebastianbergmann/phpunit/issues/3016
    }

    /**
     * This test checks that the workarounds in place for https://github.com/mozilla/geckodriver/issues/653 work as
     * expected where child links can be clicked.
     *
     * @covers ::click
     * @covers ::clickChildElement
     * @group exclude-chrome
     * @group exclude-edge
     */
    public function testGeckoDriverShouldClickOnBlockLevelElement(): void
    {
        self::skipForUnmatchedBrowsers(['firefox']);

        $links = [
            'a-index-plain',
            'a-index-block-child',
            'a-index-block-child-hidden',
            'a-index-second-child-hidden',
        ];

        foreach ($links as $linkid) {
            $this->driver->get($this->getTestPageUrl(TestPage::GECKO_653));
            $linkElement = $this->driver->findElement(WebDriverBy::id($linkid));

            $linkElement->click();
            $this->assertStringContainsString('index.html', $this->driver->getCurrentUrl());
        }
    }

    /**
     * This test checks that the workarounds in place for https://github.com/mozilla/geckodriver/issues/653 work as
     * expected where child links cannot be clicked, and that appropriate exceptions are thrown.
     *
     * @covers ::click
     * @covers ::clickChildElement
     * @group exclude-chrome
     * @group exclude-edge
     */
    public function testGeckoDriverShouldClickNotInteractable(): void
    {
        self::skipForUnmatchedBrowsers(['firefox']);

        $this->driver->get($this->getTestPageUrl(TestPage::GECKO_653));

        $linkElement = $this->driver->findElement(WebDriverBy::id('a-index-plain-hidden'));

        try {
            $linkElement->click();
            $this->fail('No exception was thrown when clicking an inaccessible link');
        } catch (ElementNotInteractableException $e) {
            $this->assertInstanceOf(ElementNotInteractableException::class, $e);
        }

        $linkElement = $this->driver->findElement(WebDriverBy::id('a-index-hidden-block-child'));

        try {
            $linkElement->click();
            $this->fail('No exception was thrown when clicking an inaccessible link');
        } catch (ElementNotInteractableException $e) {
            $this->assertInstanceOf(ElementNotInteractableException::class, $e);
        }
    }

    /**
     * @covers ::clear
     */
    public function testShouldClearFormElementText(): void
    {
        $this->driver->get($this->getTestPageUrl(TestPage::FORM));

        $input = $this->driver->findElement(WebDriverBy::id('input-text'));
        $textarea = $this->driver->findElement(WebDriverBy::id('textarea'));

        $this->assertSame('Default input text', $input->getAttribute('value'));
        $input->clear();
        $this->assertSame('', $input->getAttribute('value'));

        $this->assertSame('Default textarea text', $textarea->getAttribute('value'));
        $textarea->clear();
        $this->assertSame('', $textarea->getAttribute('value'));
    }

    /**
     * @covers ::sendKeys
     */
    public function testShouldSendKeysToFormElement(): void
    {
        $this->driver->get($this->getTestPageUrl(TestPage::FORM));

        $input = $this->driver->findElement(WebDriverBy::id('input-text'));
        $textarea = $this->driver->findElement(WebDriverBy::id('textarea'));

        $input->clear();
        $input->sendKeys('foo bar');
        $this->assertSame('foo bar', $input->getAttribute('value'));
        $input->sendKeys(' baz');
        $this->assertSame('foo bar baz', $input->getAttribute('value'));

        $input->clear();
        $input->sendKeys([WebDriverKeys::SHIFT, 'H', WebDriverKeys::NULL, 'ello']);
        $this->assertSame('Hello', $input->getAttribute('value'));

        $textarea->clear();
        $textarea->sendKeys('foo bar');
        $this->assertSame('foo bar', $textarea->getAttribute('value'));
        $textarea->sendKeys(' baz');
        $this->assertSame('foo bar baz', $textarea->getAttribute('value'));

        $textarea->clear();
        $textarea->sendKeys([WebDriverKeys::SHIFT, 'H', WebDriverKeys::NULL, 'ello']);
        $this->assertSame('Hello', $textarea->getAttribute('value'));

        // Send keys as array
        $textarea->clear();
        $textarea->sendKeys(['bat', 1, '3', ' ', 3, '7']);
        $this->assertSame('bat13 37', $textarea->getAttribute('value'));
    }

    /**
     * @covers ::isDisplayed
     * @covers \Facebook\WebDriver\Remote\RemoteWebDriver::execute
     * @covers \Facebook\WebDriver\Support\IsElementDisplayedAtom
     */
    public function testShouldDetectElementDisplayedness(): void
    {
        $this->driver->get($this->getTestPageUrl(TestPage::INDEX));

        $visibleElement = $this->driver->findElement(WebDriverBy::cssSelector('.test_class'));
        $elementOutOfViewport = $this->driver->findElement(WebDriverBy::id('element-out-of-viewport'));
        $hiddenElement = $this->driver->findElement(WebDriverBy::id('hidden-element'));

        $this->assertTrue($visibleElement->isDisplayed());
        $this->assertTrue($elementOutOfViewport->isDisplayed());
        $this->assertFalse($hiddenElement->isDisplayed());
    }

    /**
     * @covers ::isEnabled
     */
    public function testShouldDetectEnabledInputs(): void
    {
        $this->driver->get($this->getTestPageUrl(TestPage::FORM));

        $inputEnabled = $this->driver->findElement(WebDriverBy::id('input-text'));
        $inputDisabled = $this->driver->findElement(WebDriverBy::id('input-text-disabled'));

        $this->assertTrue($inputEnabled->isEnabled());
        $this->assertFalse($inputDisabled->isEnabled());
    }

    /**
     * @covers ::isSelected
     */
    public function testShouldSelectedInputsOrOptions(): void
    {
        $this->driver->get($this->getTestPageUrl(TestPage::FORM));

        $checkboxSelected = $this->driver->findElement(
            WebDriverBy::cssSelector('input[name=checkbox][value=second]')
        );
        $checkboxNotSelected = $this->driver->findElement(
            WebDriverBy::cssSelector('input[name=checkbox][value=first]')
        );
        $this->assertTrue($checkboxSelected->isSelected());
        $this->assertFalse($checkboxNotSelected->isSelected());

        $radioSelected = $this->driver->findElement(WebDriverBy::cssSelector('input[name=radio][value=second]'));
        $radioNotSelected = $this->driver->findElement(WebDriverBy::cssSelector('input[name=radio][value=first]'));
        $this->assertTrue($radioSelected->isSelected());
        $this->assertFalse($radioNotSelected->isSelected());

        $optionSelected = $this->driver->findElement(WebDriverBy::cssSelector('#select option[value=first]'));
        $optionNotSelected = $this->driver->findElement(WebDriverBy::cssSelector('#select option[value=second]'));
        $this->assertTrue($optionSelected->isSelected());
        $this->assertFalse($optionNotSelected->isSelected());
    }

    /**
     * @covers ::submit
     * @group exclude-edge
     */
    public function testShouldSubmitFormBySubmitEventOnForm(): void
    {
        $this->driver->get($this->getTestPageUrl(TestPage::FORM));

        $formElement = $this->driver->findElement(WebDriverBy::cssSelector('form'));

        $formElement->submit();

        $this->driver->wait()->until(
            WebDriverExpectedCondition::titleIs('Form submit endpoint')
        );

        $this->assertSame('Received POST data', $this->driver->findElement(WebDriverBy::cssSelector('h2'))->getText());
    }

    /**
     * @covers ::submit
     */
    public function testShouldSubmitFormBySubmitEventOnFormInputElement(): void
    {
        $this->driver->get($this->getTestPageUrl(TestPage::FORM));

        $inputTextElement = $this->driver->findElement(WebDriverBy::id('input-text'));

        $inputTextElement->submit();

        $this->driver->wait()->until(
            WebDriverExpectedCondition::titleIs('Form submit endpoint')
        );

        $this->assertSame('Received POST data', $this->driver->findElement(WebDriverBy::cssSelector('h2'))->getText());
    }

    /**
     * @covers ::click
     */
    public function testShouldSubmitFormByClickOnSubmitInput(): void
    {
        $this->driver->get($this->getTestPageUrl(TestPage::FORM));

        $submitElement = $this->driver->findElement(WebDriverBy::id('submit'));

        $submitElement->click();

        $this->driver->wait()->until(
            WebDriverExpectedCondition::titleIs('Form submit endpoint')
        );

        $this->assertSame('Received POST data', $this->driver->findElement(WebDriverBy::cssSelector('h2'))->getText());
    }

    /**
     * @covers ::equals
     */
    public function testShouldCompareEqualsElement(): void
    {
        $this->driver->get($this->getTestPageUrl(TestPage::INDEX));

        $firstElement = $this->driver->findElement(WebDriverBy::cssSelector('ul.list'));
        $differentElement = $this->driver->findElement(WebDriverBy::cssSelector('#text-simple'));
        $againTheFirstElement = $this->driver->findElement(WebDriverBy::cssSelector('ul.list'));

        $this->assertTrue($firstElement->equals($againTheFirstElement));
        $this->assertTrue($againTheFirstElement->equals($firstElement));

        $this->assertFalse($differentElement->equals($firstElement));
        $this->assertFalse($firstElement->equals($differentElement));
        $this->assertFalse($differentElement->equals($againTheFirstElement));
    }

    /**
     * @covers ::findElement
     */
    public function testShouldThrowExceptionIfChildElementCannotBeFound(): void
    {
        $this->driver->get($this->getTestPageUrl(TestPage::INDEX));
        $element = $this->driver->findElement(WebDriverBy::cssSelector('ul.list'));

        $this->expectException(NoSuchElementException::class);
        $element->findElement(WebDriverBy::id('not_existing'));
    }

    public function testShouldFindChildElementIfExistsOnAPage(): void
    {
        $this->driver->get($this->getTestPageUrl(TestPage::INDEX));
        $element = $this->driver->findElement(WebDriverBy::cssSelector('ul.list'));

        $childElement = $element->findElement(WebDriverBy::cssSelector('li'));

        $this->assertInstanceOf(RemoteWebElement::class, $childElement);
        $this->assertSame('li', $childElement->getTagName());
        $this->assertSame('First', $childElement->getText());
    }

    public function testShouldReturnEmptyArrayIfChildElementsCannotBeFound(): void
    {
        $this->driver->get($this->getTestPageUrl(TestPage::INDEX));
        $element = $this->driver->findElement(WebDriverBy::cssSelector('ul.list'));

        $childElements = $element->findElements(WebDriverBy::cssSelector('not_existing'));

        $this->assertIsArray($childElements);
        $this->assertCount(0, $childElements);
    }

    public function testShouldFindMultipleChildElements(): void
    {
        $this->driver->get($this->getTestPageUrl(TestPage::INDEX));
        $element = $this->driver->findElement(WebDriverBy::cssSelector('ul.list'));

        $allElements = $this->driver->findElements(WebDriverBy::cssSelector('li'));
        $childElements = $element->findElements(WebDriverBy::cssSelector('li'));

        $this->assertIsArray($childElements);
        $this->assertCount(5, $allElements); // there should be 5 <li> elements on page
        $this->assertCount(3, $childElements); // but we should find only subelements of one <ul>
        $this->assertContainsOnlyInstancesOf(RemoteWebElement::class, $childElements);
    }

    /**
     * @covers ::takeElementScreenshot
     * @covers \Facebook\WebDriver\Support\ScreenshotHelper
     * @group exclude-saucelabs
     */
    public function testShouldTakeAndSaveElementScreenshot(): void
    {
        self::skipForJsonWireProtocol('Take element screenshot is only part of W3C protocol');

        if (!extension_loaded('gd')) {
            $this->markTestSkipped('GD extension must be enabled');
        }

        // When running this test on real devices, it has a retina display so 5px will be converted into 10px
        $isCi = (new CiDetector())->isCiDetected();
        $isSafari = getenv('BROWSER_NAME') === 'safari';

        $screenshotPath = sys_get_temp_dir() . '/' . uniqid('php-webdriver-') . '/element-screenshot.png';

        $this->driver->get($this->getTestPageUrl(TestPage::INDEX));

        $element = $this->driver->findElement(WebDriverBy::id('red-box'));

        $outputPngString = $element->takeElementScreenshot($screenshotPath);

        // Assert file output
        $imageFromFile = imagecreatefrompng($screenshotPath);

        if ($isSafari && !$isCi) {
            $this->assertEquals(10, imagesx($imageFromFile));
            $this->assertEquals(10, imagesy($imageFromFile));
        } else {
            $this->assertEquals(5, imagesx($imageFromFile));
            $this->assertEquals(5, imagesy($imageFromFile));
        }

        // Validate element is actually red
        $this->assertSame(
            ['red' => 255, 'green' => 0, 'blue' => 0, 'alpha' => 0],
            imagecolorsforindex($imageFromFile, imagecolorat($imageFromFile, 0, 0))
        );

        // Assert string output
        $imageFromString = imagecreatefromstring($outputPngString);
        if (version_compare(phpversion(), '8.0.0', '>=')) {
            $this->assertInstanceOf(\GdImage::class, $imageFromString);
        } else {
            $this->assertTrue(is_resource($imageFromString));
        }

        if ($isSafari && !$isCi) {
            $this->assertEquals(10, imagesx($imageFromString));
            $this->assertEquals(10, imagesy($imageFromString));
        } else {
            $this->assertEquals(5, imagesx($imageFromString));
            $this->assertEquals(5, imagesy($imageFromString));
        }

        // Validate element is actually red
        $this->assertSame(
            ['red' => 255, 'green' => 0, 'blue' => 0, 'alpha' => 0],
            imagecolorsforindex($imageFromString, imagecolorat($imageFromString, 0, 0))
        );

        unlink($screenshotPath);
        rmdir(dirname($screenshotPath));
    }
}
