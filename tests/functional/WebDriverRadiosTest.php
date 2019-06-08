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
use Facebook\WebDriver\Exception\UnsupportedOperationException;

/**
 * @covers \Facebook\WebDriver\WebDriverRadios
 * @covers \Facebook\WebDriver\AbstractWebDriverCheckboxOrRadio
 */
class WebDriverRadiosTest extends WebDriverTestCase
{
    protected function setUp()
    {
        parent::setUp();

        $this->driver->get($this->getTestPageUrl('form_checkbox_radio.html'));
    }

    public function testIsMultiple()
    {
        $c = new WebDriverRadios($this->driver->findElement(WebDriverBy::xpath('//input[@type="radio"]')));
        $this->assertFalse($c->isMultiple());
    }

    public function testGetOptions()
    {
        $c = new WebDriverRadios($this->driver->findElement(WebDriverBy::xpath('//input[@type="radio"]')));
        $values = [];
        foreach ($c->getOptions() as $option) {
            $values[] = $option->getAttribute('value');
        }

        $this->assertSame(['j3a', 'j3b', 'j3c'], $values);
    }

    public function testGetFirstSelectedOption()
    {
        $c = new WebDriverRadios($this->driver->findElement(WebDriverBy::xpath('//input[@type="radio"]')));
        $c->selectByValue('j3a');
        $this->assertSame('j3a', $c->getFirstSelectedOption()->getAttribute('value'));
    }

    public function testSelectByValue()
    {
        $c = new WebDriverRadios($this->driver->findElement(WebDriverBy::xpath('//input[@type="radio"]')));
        $c->selectByValue('j3b');

        $selectedOptions = $c->getAllSelectedOptions();
        $this->assertCount(1, $selectedOptions);
        $this->assertSame('j3b', $selectedOptions[0]->getAttribute('value'));
    }

    public function testSelectByValueInvalid()
    {
        $c = new WebDriverRadios($this->driver->findElement(WebDriverBy::xpath('//input[@type="radio"]')));

        $this->expectException(NoSuchElementException::class);
        $this->expectExceptionMessage('Cannot locate radio with value: notexist');
        $c->selectByValue('notexist');
    }

    public function testSelectByIndex()
    {
        $c = new WebDriverRadios($this->driver->findElement(WebDriverBy::xpath('//input[@type="radio"]')));
        $c->selectByIndex(1);

        $allSelectedOptions = $c->getAllSelectedOptions();
        $this->assertCount(1, $allSelectedOptions);
        $this->assertSame('j3b', $allSelectedOptions[0]->getAttribute('value'));
    }

    public function testSelectByIndexInvalid()
    {
        $c = new WebDriverRadios($this->driver->findElement(WebDriverBy::xpath('//input[@type="radio"]')));

        $this->expectException(NoSuchElementException::class);
        $this->expectExceptionMessage('Cannot locate radio with index: ' . PHP_INT_MAX);
        $c->selectByIndex(PHP_INT_MAX);
    }

    /**
     * @dataProvider selectByVisibleTextDataProvider
     *
     * @param string $text
     * @param string $value
     */
    public function testSelectByVisibleText($text, $value)
    {
        $c = new WebDriverRadios($this->driver->findElement(WebDriverBy::xpath('//input[@type="radio"]')));
        $c->selectByVisibleText($text);
        $this->assertSame($value, $c->getFirstSelectedOption()->getAttribute('value'));
    }

    /**
     * @return array
     */
    public function selectByVisibleTextDataProvider()
    {
        return [
            ['J 3 B', 'j3b'],
            ['J3C', 'j3c'],
        ];
    }

    /**
     * @dataProvider selectByVisiblePartialTextDataProvider
     *
     * @param string $text
     * @param string $value
     */
    public function testSelectByVisiblePartialText($text, $value)
    {
        $c = new WebDriverRadios($this->driver->findElement(WebDriverBy::xpath('//input[@type="radio"]')));
        $c->selectByVisiblePartialText($text);
        $this->assertSame($value, $c->getFirstSelectedOption()->getAttribute('value'));
    }

    /**
     * @return array
     */
    public function selectByVisiblePartialTextDataProvider()
    {
        return [
            ['3 B', 'j3b'],
            ['3C', 'j3c'],
        ];
    }

    public function testDeselectAllRadio()
    {
        $c = new WebDriverRadios($this->driver->findElement(WebDriverBy::xpath('//input[@type="radio"]')));

        $this->expectException(UnsupportedOperationException::class);
        $this->expectExceptionMessage('You cannot deselect radio buttons');
        $c->deselectAll();
    }

    public function testDeselectByIndexRadio()
    {
        $c = new WebDriverRadios($this->driver->findElement(WebDriverBy::xpath('//input[@type="radio"]')));

        $this->expectException(UnsupportedOperationException::class);
        $this->expectExceptionMessage('You cannot deselect radio buttons');
        $c->deselectByIndex(0);
    }

    public function testDeselectByValueRadio()
    {
        $c = new WebDriverRadios($this->driver->findElement(WebDriverBy::xpath('//input[@type="radio"]')));

        $this->expectException(UnsupportedOperationException::class);
        $this->expectExceptionMessage('You cannot deselect radio buttons');
        $c->deselectByValue('val');
    }

    public function testDeselectByVisibleTextRadio()
    {
        $c = new WebDriverRadios($this->driver->findElement(WebDriverBy::xpath('//input[@type="radio"]')));

        $this->expectException(UnsupportedOperationException::class);
        $this->expectExceptionMessage('You cannot deselect radio buttons');
        $c->deselectByVisibleText('AB');
    }

    public function testDeselectByVisiblePartialTextRadio()
    {
        $c = new WebDriverRadios($this->driver->findElement(WebDriverBy::xpath('//input[@type="radio"]')));

        $this->expectException(UnsupportedOperationException::class);
        $this->expectExceptionMessage('You cannot deselect radio buttons');
        $c->deselectByVisiblePartialText('AB');
    }
}
