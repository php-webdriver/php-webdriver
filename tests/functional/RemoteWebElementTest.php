<?php
// Copyright 2004-present Facebook. All Rights Reserved.
//
// Licensed under the Apache License, Version 2.0 (the "License");
// you may not use this file except in compliance with the License.
// You may obtain a copy of the License at
//
//     http://www.apache.org/licenses/LICENSE-2.0
//
// Unless required by applicable law or agreed to in writing, software
// distributed under the License is distributed on an "AS IS" BASIS,
// WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
// See the License for the specific language governing permissions and
// limitations under the License.

namespace Facebook\WebDriver;

use Facebook\WebDriver\Exception\NoSuchElementException;
use Facebook\WebDriver\Remote\RemoteWebElement;

/**
 * @coversDefaultClass \Facebook\WebDriver\Remote\RemoteWebElement
 */
class RemoteWebElementTest extends WebDriverTestCase
{
    /**
     * @covers ::getText
     * @group exclude-edge
     * https://developer.microsoft.com/en-us/microsoft-edge/platform/issues/5569343/
     */
    public function testShouldGetText()
    {
        $this->driver->get($this->getTestPageUrl('index.html'));
        $elementWithSimpleText = $this->driver->findElement(WebDriverBy::id('text-simple'));
        $elementWithTextWithSpaces = $this->driver->findElement(WebDriverBy::id('text-with-spaces'));

        $this->assertEquals('Foo bar text', $elementWithSimpleText->getText());
        $this->assertEquals('Multiple spaces are stripped', $elementWithTextWithSpaces->getText());
    }

    /**
     * @covers ::getAttribute
     */
    public function testShouldGetAttributeValue()
    {
        $this->driver->get($this->getTestPageUrl('index.html'));

        $element = $this->driver->findElement(WebDriverBy::id('text-simple'));

        $this->assertSame('note', $element->getAttribute('role'));
        $this->assertSame('height: 5em; border: 1px solid black;', $element->getAttribute('style'));
        $this->assertSame('text-simple', $element->getAttribute('id'));
    }

    /**
     * @covers ::getLocation
     */
    public function testShouldGetLocation()
    {
        $this->driver->get($this->getTestPageUrl('index.html'));

        $element = $this->driver->findElement(WebDriverBy::id('element-with-location'));

        $elementLocation = $element->getLocation();
        $this->assertInstanceOf(WebDriverPoint::class, $elementLocation);
        $this->assertSame(33, $elementLocation->getX());
        $this->assertSame(500, $elementLocation->getY());
    }

    /**
     * @covers ::getSize
     */
    public function testShouldGetSize()
    {
        $this->driver->get($this->getTestPageUrl('index.html'));

        $element = $this->driver->findElement(WebDriverBy::id('element-with-location'));

        $elementSize = $element->getSize();
        $this->assertInstanceOf(WebDriverDimension::class, $elementSize);
        $this->assertSame(333, $elementSize->getWidth());
        $this->assertSame(66, $elementSize->getHeight());
    }

    /**
     * @covers ::getCSSValue
     */
    public function testShouldGetCssValue()
    {
        $this->driver->get($this->getTestPageUrl('index.html'));

        $elementWithBorder = $this->driver->findElement(WebDriverBy::id('text-simple'));
        $elementWithoutBorder = $this->driver->findElement(WebDriverBy::id('text-with-spaces'));

        $this->assertSame('solid', $elementWithBorder->getCSSValue('border-left-style'));
        $this->assertSame('none', $elementWithoutBorder->getCSSValue('border-left-style'));

        // Browser could report color in either rgb (like MS Edge) or rgba (like everyone else)
        $this->assertRegExp(
            '/rgba?\(0, 0, 0(, 1)?\)/',
            $elementWithBorder->getCSSValue('border-left-color')
        );
    }

    /**
     * @covers ::getTagName
     */
    public function testShouldGetTagName()
    {
        $this->driver->get($this->getTestPageUrl('index.html'));

        $paragraphElement = $this->driver->findElement(WebDriverBy::id('id_test'));

        $this->assertSame('p', $paragraphElement->getTagName());
    }

    /**
     * @covers ::click
     */
    public function testShouldClick()
    {
        $this->driver->get($this->getTestPageUrl('index.html'));
        $linkElement = $this->driver->findElement(WebDriverBy::id('a-form'));

        $linkElement->click();

        $this->driver->wait()->until(
            WebDriverExpectedCondition::urlContains('form.html')
        );
    }

    /**
     * @covers ::clear
     */
    public function testShouldClearFormElementText()
    {
        $this->driver->get($this->getTestPageUrl('form.html'));

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
    public function testShouldSendKeysToFormElement()
    {
        $this->driver->get($this->getTestPageUrl('form.html'));

        $input = $this->driver->findElement(WebDriverBy::id('input-text'));
        $textarea = $this->driver->findElement(WebDriverBy::id('textarea'));

        $input->clear();
        $input->sendKeys('foo bar');
        $this->assertSame('foo bar', $input->getAttribute('value'));
        $input->sendKeys(' baz');
        $this->assertSame('foo bar baz', $input->getAttribute('value'));

        $textarea->clear();
        $textarea->sendKeys('foo bar');
        $this->assertSame('foo bar', $textarea->getAttribute('value'));
        $textarea->sendKeys(' baz');
        $this->assertSame('foo bar baz', $textarea->getAttribute('value'));
    }

    /**
     * @covers ::isEnabled
     */
    public function testShouldDetectEnabledInputs()
    {
        $this->driver->get($this->getTestPageUrl('form.html'));

        $inputEnabled = $this->driver->findElement(WebDriverBy::id('input-text'));
        $inputDisabled = $this->driver->findElement(WebDriverBy::id('input-text-disabled'));

        $this->assertTrue($inputEnabled->isEnabled());
        $this->assertFalse($inputDisabled->isEnabled());
    }

    /**
     * @covers ::isSelected
     */
    public function testShouldSelectedInputsOrOptions()
    {
        $this->driver->get($this->getTestPageUrl('form.html'));

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
    public function testShouldSubmitFormBySubmitEventOnForm()
    {
        $this->driver->get($this->getTestPageUrl('form.html'));

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
    public function testShouldSubmitFormBySubmitEventOnFormInputElement()
    {
        $this->driver->get($this->getTestPageUrl('form.html'));

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
    public function testShouldSubmitFormByClickOnSubmitInput()
    {
        $this->driver->get($this->getTestPageUrl('form.html'));

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
    public function testShouldCompareEqualsElement()
    {
        $this->driver->get($this->getTestPageUrl('index.html'));

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
    public function testShouldThrowExceptionIfChildElementCannotBeFound()
    {
        $this->driver->get($this->getTestPageUrl('index.html'));
        $element = $this->driver->findElement(WebDriverBy::cssSelector('ul.list'));

        $this->expectException(NoSuchElementException::class);
        $element->findElement(WebDriverBy::id('not_existing'));
    }

    public function testShouldFindChildElementIfExistsOnAPage()
    {
        $this->driver->get($this->getTestPageUrl('index.html'));
        $element = $this->driver->findElement(WebDriverBy::cssSelector('ul.list'));

        $childElement = $element->findElement(WebDriverBy::cssSelector('li'));

        $this->assertInstanceOf(RemoteWebElement::class, $childElement);
        $this->assertSame('li', $childElement->getTagName());
        $this->assertSame('First', $childElement->getText());
    }

    public function testShouldReturnEmptyArrayIfChildElementsCannotBeFound()
    {
        $this->driver->get($this->getTestPageUrl('index.html'));
        $element = $this->driver->findElement(WebDriverBy::cssSelector('ul.list'));

        $childElements = $element->findElements(WebDriverBy::cssSelector('not_existing'));

        $this->assertInternalType('array', $childElements);
        $this->assertCount(0, $childElements);
    }

    public function testShouldFindMultipleChildElements()
    {
        $this->driver->get($this->getTestPageUrl('index.html'));
        $element = $this->driver->findElement(WebDriverBy::cssSelector('ul.list'));

        $allElements = $this->driver->findElements(WebDriverBy::cssSelector('li'));
        $childElements = $element->findElements(WebDriverBy::cssSelector('li'));

        $this->assertInternalType('array', $childElements);
        $this->assertCount(5, $allElements); // there should be 5 <li> elements on page
        $this->assertCount(3, $childElements); // but we should find only subelements of one <ul>
        $this->assertContainsOnlyInstancesOf(RemoteWebElement::class, $childElements);
    }
}
