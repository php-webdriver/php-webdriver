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

class WebDriverCheckboxTest extends WebDriverTestCase
{
    protected function setUp()
    {
        parent::setUp();

        $this->driver->get($this->getTestPageUrl('form_checkbox_radio.html'));
    }

    public function testIsMultiple()
    {
        $c = new WebDriverCheckbox($this->driver->findElement(WebDriverBy::xpath('//input[@type="checkbox"]')));
        $this->assertTrue($c->isMultiple());

        $c = new WebDriverCheckbox($this->driver->findElement(WebDriverBy::xpath('//input[@type="radio"]')));
        $this->assertFalse($c->isMultiple());
    }

    /**
     * @dataProvider getOptionsDataProvider
     *
     * @param string $type
     * @param string[] $options
     */
    public function testGetOptions($type, array $options)
    {
        $c = new WebDriverCheckbox($this->driver->findElement(WebDriverBy::xpath("//input[@type='$type']")));
        $values = [];
        foreach ($c->getOptions() as $option) {
            $values[] = $option->getAttribute('value');
        }

        $this->assertSame($options, $values);
    }

    /**
     * @return array
     */
    public function getOptionsDataProvider()
    {
        return [
            ['checkbox', ['j2a', 'j2b', 'j2c']],
            ['radio', ['j3a', 'j3b', 'j3c']],
        ];
    }

    public function testGetFirstSelectedOption()
    {
        $c = new WebDriverCheckbox($this->driver->findElement(WebDriverBy::xpath('//input[@type="checkbox"]')));
        $c->selectByValue('j2a');
        $this->assertSame('j2a', $c->getFirstSelectedOption()->getAttribute('value'));

        $c = new WebDriverCheckbox($this->driver->findElement(WebDriverBy::xpath('//input[@type="radio"]')));
        $c->selectByValue('j3a');
        $this->assertSame('j3a', $c->getFirstSelectedOption()->getAttribute('value'));
    }

    /**
     * @dataProvider selectByValueDataProvider
     *
     * @param string $type
     * @param string[] $selectedOptions
     */
    public function testSelectByValue($type, array $selectedOptions)
    {
        $c = new WebDriverCheckbox($this->driver->findElement(WebDriverBy::xpath("//input[@type='$type']")));
        foreach ($selectedOptions as $index => $selectedOption) {
            $c->selectByValue($selectedOption);
        }

        $selectedValues = [];
        foreach ($c->getAllSelectedOptions() as $option) {
            $selectedValues[] = $option->getAttribute('value');
        }
        $this->assertSame($selectedOptions, $selectedValues);
    }

    /**
     * @return array
     */
    public function selectByValueDataProvider()
    {
        return [
            ['checkbox', ['j2b', 'j2c']],
            ['radio', ['j3b']],
        ];
    }

    public function testSelectByValueInvalid()
    {
        $this->expectException(NoSuchElementException::class);

        $c = new WebDriverCheckbox($this->driver->findElement(WebDriverBy::xpath('//input[@type="checkbox"]')));
        $c->selectByValue('notexist');
    }

    /**
     * @dataProvider selectByIndexDataProvider
     *
     * @param string $type
     * @param string[] $selectedOptions
     */
    public function testSelectByIndex($type, array $selectedOptions)
    {
        $c = new WebDriverCheckbox($this->driver->findElement(WebDriverBy::xpath("//input[@type='$type']")));
        foreach ($selectedOptions as $index => $selectedOption) {
            $c->selectByIndex($index);
        }

        $selectedValues = [];
        foreach ($c->getAllSelectedOptions() as $option) {
            $selectedValues[] = $option->getAttribute('value');
        }
        $this->assertSame(array_values($selectedOptions), $selectedValues);
    }

    /**
     * @return array
     */
    public function selectByIndexDataProvider()
    {
        return [
            ['checkbox', [1 => 'j2b', 2 => 'j2c']],
            ['radio', [1 => 'j3b']],
        ];
    }

    public function testSelectByIndexInvalid()
    {
        $c = new WebDriverCheckbox($this->driver->findElement(WebDriverBy::xpath('//input[@type="checkbox"]')));

        $this->expectException(NoSuchElementException::class);
        $this->expectExceptionMessage('Cannot locate option with index: '.PHP_INT_MAX);
        $c->selectByIndex(PHP_INT_MAX);
    }

    /**
     * @dataProvider selectByVisibleTextDataProvider
     *
     * @param string $type
     * @param string $text
     * @param string $value
     */
    public function testSelectByVisibleText($type, $text, $value)
    {
        $c = new WebDriverCheckbox($this->driver->findElement(WebDriverBy::xpath("//input[@type='$type']")));
        $c->selectByVisibleText($text);
        $this->assertSame($value, $c->getFirstSelectedOption()->getAttribute('value'));
    }

    /**
     * @return array
     */
    public function selectByVisibleTextDataProvider()
    {
        return [
            ['checkbox', 'J2B', 'j2b'],
            ['checkbox', 'J2C', 'j2c'],
            ['radio', 'J3B', 'j3b'],
            ['radio', 'J3C', 'j3c'],
        ];
    }

    /**
     * @dataProvider selectByVisiblePartialTextDataProvider
     *
     * @param string $type
     * @param string $text
     * @param string $value
     */
    public function testSelectByVisiblePartialText($type, $text, $value)
    {
        $c = new WebDriverCheckbox($this->driver->findElement(WebDriverBy::xpath("//input[@type='$type']")));
        $c->selectByVisiblePartialText($text);
        $this->assertSame($value, $c->getFirstSelectedOption()->getAttribute('value'));
    }

    /**
     * @return array
     */
    public function selectByVisiblePartialTextDataProvider()
    {
        return [
            ['checkbox', '2B', 'j2b'],
            ['checkbox', '2C', 'j2c'],
            ['radio', '3B', 'j3b'],
            ['radio', '3C', 'j3c'],
        ];
    }

    public function testDeselectAll()
    {
        $c = new WebDriverCheckbox($this->driver->findElement(WebDriverBy::xpath('//input[@type="checkbox"]')));

        $c->selectByIndex(0);
        $this->assertCount(1, $c->getAllSelectedOptions());
        $c->deselectAll();
        $this->assertEmpty($c->getAllSelectedOptions());
    }

    public function testDeselectByIndex()
    {
        $c = new WebDriverCheckbox($this->driver->findElement(WebDriverBy::xpath('//input[@type="checkbox"]')));

        $c->selectByIndex(0);
        $this->assertCount(1, $c->getAllSelectedOptions());
        $c->deselectByIndex(0);
        $this->assertEmpty($c->getAllSelectedOptions());
    }

    public function testDeselectByValue()
    {
        $c = new WebDriverCheckbox($this->driver->findElement(WebDriverBy::xpath('//input[@type="checkbox"]')));

        $c->selectByValue('j2a');
        $this->assertCount(1, $c->getAllSelectedOptions());
        $c->deselectByValue('j2a');
        $this->assertEmpty($c->getAllSelectedOptions());
    }

    public function testDeselectByVisibleText()
    {
        $c = new WebDriverCheckbox($this->driver->findElement(WebDriverBy::xpath('//input[@type="checkbox"]')));

        $c->selectByVisibleText('J2B');
        $this->assertCount(1, $c->getAllSelectedOptions());
        $c->deselectByVisibleText('J2B');
        $this->assertEmpty($c->getAllSelectedOptions());
    }

    public function testDeselectByVisiblePartialText()
    {
        $c = new WebDriverCheckbox($this->driver->findElement(WebDriverBy::xpath('//input[@type="checkbox"]')));

        $c->selectByVisiblePartialText('2C');
        $this->assertCount(1, $c->getAllSelectedOptions());
        $c->deselectByVisiblePartialText('2C');
        $this->assertEmpty($c->getAllSelectedOptions());
    }

    public function testDeselectAllRadio()
    {
        $c = new WebDriverCheckbox($this->driver->findElement(WebDriverBy::xpath('//input[@type="radio"]')));

        $this->expectException(UnsupportedOperationException::class);
        $this->expectExceptionMessage('You may only deselect all options of checkboxes');
        $c->deselectAll();
    }

    public function testDeselectByIndexRadio()
    {
        $c = new WebDriverCheckbox($this->driver->findElement(WebDriverBy::xpath('//input[@type="radio"]')));

        $this->expectException(UnsupportedOperationException::class);
        $this->expectExceptionMessage('You may only deselect checkboxes');
        $c->deselectByIndex(0);
    }

    public function testDeselectByValueRadio()
    {
        $c = new WebDriverCheckbox($this->driver->findElement(WebDriverBy::xpath('//input[@type="radio"]')));

        $this->expectException(UnsupportedOperationException::class);
        $this->expectExceptionMessage('You may only deselect checkboxes');
        $c->deselectByValue('val');
    }

    public function testDeselectByVisibleTextRadio()
    {
        $c = new WebDriverCheckbox($this->driver->findElement(WebDriverBy::xpath('//input[@type="radio"]')));

        $this->expectException(UnsupportedOperationException::class);
        $this->expectExceptionMessage('You may only deselect checkboxes');
        $c->deselectByVisibleText('AB');
    }

    public function testDeselectByVisiblePartialTextRadio()
    {
        $c = new WebDriverCheckbox($this->driver->findElement(WebDriverBy::xpath('//input[@type="radio"]')));

        $this->expectException(UnsupportedOperationException::class);
        $this->expectExceptionMessage('You may only deselect checkboxes');
        $c->deselectByVisiblePartialText('AB');
    }
}
