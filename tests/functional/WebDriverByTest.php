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

use Facebook\WebDriver\Remote\RemoteWebElement;

/**
 * Tests for locator strategies provided by WebDriverBy.
 * @covers \Facebook\WebDriver\WebDriverBy
 */
class WebDriverByTest extends WebDriverTestCase
{
    /**
     * @dataProvider textElementsProvider
     * @param string $webDriverByLocatorMethod
     * @param string $webDriverByLocatorValue
     * @param string $expectedText
     * @param string $expectedAttributeValue
     */
    public function testShouldFindTextElementByLocator(
        $webDriverByLocatorMethod,
        $webDriverByLocatorValue,
        $expectedText = null,
        $expectedAttributeValue = null
    ) {
        $this->driver->get($this->getTestPageUrl('index.html'));

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

    public function textElementsProvider()
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
