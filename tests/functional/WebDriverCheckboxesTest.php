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

/**
 * @covers \Facebook\WebDriver\WebDriverCheckboxes
 * @covers \Facebook\WebDriver\AbstractWebDriverCheckboxOrRadio
 */
class WebDriverCheckboxesTest extends WebDriverTestCase
{
    protected function setUp()
    {
        parent::setUp();

        $this->driver->get($this->getTestPageUrl('form_checkbox_radio.html'));
    }

    public function testIsMultiple()
    {
        $c = new WebDriverCheckboxes($this->driver->findElement(WebDriverBy::xpath('//input[@type="checkbox"]')));
        $this->assertTrue($c->isMultiple());
    }

    public function testGetOptions()
    {
        $c = new WebDriverCheckboxes(
            $this->driver->findElement(WebDriverBy::xpath('//form[2]//input[@type="checkbox"]'))
        );
        $this->assertNotEmpty($c->getOptions());
    }

    public function testGetFirstSelectedOption()
    {
        $c = new WebDriverCheckboxes($this->driver->findElement(WebDriverBy::xpath('//input[@type="checkbox"]')));
        $c->selectByValue('j2a');
        $this->assertSame('j2a', $c->getFirstSelectedOption()->getAttribute('value'));
    }

    public function testGetFirstSelectedOptionWithSameNameDifferentForm()
    {
        $radio = new WebDriverCheckboxes($this->driver->findElement(WebDriverBy::xpath('//input[@id="j5b"]')));
        $this->assertEquals('j5b', $radio->getFirstSelectedOption()->getAttribute('value'));
    }

    public function testSelectByValue()
    {
        $selectedOptions = ['j2b', 'j2c'];

        $c = new WebDriverCheckboxes($this->driver->findElement(WebDriverBy::xpath('//input[@type="checkbox"]')));
        foreach ($selectedOptions as $index => $selectedOption) {
            $c->selectByValue($selectedOption);
        }

        $selectedValues = [];
        foreach ($c->getAllSelectedOptions() as $option) {
            $selectedValues[] = $option->getAttribute('value');
        }
        $this->assertSame($selectedOptions, $selectedValues);
    }

    public function testSelectByValueInvalid()
    {
        $c = new WebDriverCheckboxes($this->driver->findElement(WebDriverBy::xpath('//input[@type="checkbox"]')));

        $this->expectException(NoSuchElementException::class);
        $this->expectExceptionMessage('Cannot locate checkbox with value: notexist');
        $c->selectByValue('notexist');
    }

    public function testSelectByIndex()
    {
        $selectedOptions = [1 => 'j2b', 2 => 'j2c'];

        $c = new WebDriverCheckboxes($this->driver->findElement(WebDriverBy::xpath('//input[@type="checkbox"]')));
        foreach ($selectedOptions as $index => $selectedOption) {
            $c->selectByIndex($index);
        }

        $selectedValues = [];
        foreach ($c->getAllSelectedOptions() as $option) {
            $selectedValues[] = $option->getAttribute('value');
        }
        $this->assertSame(array_values($selectedOptions), $selectedValues);
    }

    public function testSelectByIndexInvalid()
    {
        $c = new WebDriverCheckboxes($this->driver->findElement(WebDriverBy::xpath('//input[@type="checkbox"]')));

        $this->expectException(NoSuchElementException::class);
        $this->expectExceptionMessage('Cannot locate checkbox with index: ' . PHP_INT_MAX);
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
        $c = new WebDriverCheckboxes($this->driver->findElement(WebDriverBy::xpath('//input[@type="checkbox"]')));
        $c->selectByVisibleText($text);
        $this->assertSame($value, $c->getFirstSelectedOption()->getAttribute('value'));
    }

    /**
     * @return array
     */
    public function selectByVisibleTextDataProvider()
    {
        return [
            ['J 2 B', 'j2b'],
            ['J2C', 'j2c'],
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
        $c = new WebDriverCheckboxes($this->driver->findElement(WebDriverBy::xpath('//input[@type="checkbox"]')));
        $c->selectByVisiblePartialText($text);
        $this->assertSame($value, $c->getFirstSelectedOption()->getAttribute('value'));
    }

    /**
     * @return array
     */
    public function selectByVisiblePartialTextDataProvider()
    {
        return [
            ['2 B', 'j2b'],
            ['2C', 'j2c'],
        ];
    }

    public function testDeselectAll()
    {
        $c = new WebDriverCheckboxes($this->driver->findElement(WebDriverBy::xpath('//input[@type="checkbox"]')));

        $c->selectByIndex(0);
        $this->assertCount(1, $c->getAllSelectedOptions());
        $c->deselectAll();
        $this->assertEmpty($c->getAllSelectedOptions());
    }

    public function testDeselectByIndex()
    {
        $c = new WebDriverCheckboxes($this->driver->findElement(WebDriverBy::xpath('//input[@type="checkbox"]')));

        $c->selectByIndex(0);
        $this->assertCount(1, $c->getAllSelectedOptions());
        $c->deselectByIndex(0);
        $this->assertEmpty($c->getAllSelectedOptions());
    }

    public function testDeselectByValue()
    {
        $c = new WebDriverCheckboxes($this->driver->findElement(WebDriverBy::xpath('//input[@type="checkbox"]')));

        $c->selectByValue('j2a');
        $this->assertCount(1, $c->getAllSelectedOptions());
        $c->deselectByValue('j2a');
        $this->assertEmpty($c->getAllSelectedOptions());
    }

    public function testDeselectByVisibleText()
    {
        $c = new WebDriverCheckboxes($this->driver->findElement(WebDriverBy::xpath('//input[@type="checkbox"]')));

        $c->selectByVisibleText('J 2 B');
        $this->assertCount(1, $c->getAllSelectedOptions());
        $c->deselectByVisibleText('J 2 B');
        $this->assertEmpty($c->getAllSelectedOptions());
    }

    public function testDeselectByVisiblePartialText()
    {
        $c = new WebDriverCheckboxes($this->driver->findElement(WebDriverBy::xpath('//input[@type="checkbox"]')));

        $c->selectByVisiblePartialText('2C');
        $this->assertCount(1, $c->getAllSelectedOptions());
        $c->deselectByVisiblePartialText('2C');
        $this->assertEmpty($c->getAllSelectedOptions());
    }
}
