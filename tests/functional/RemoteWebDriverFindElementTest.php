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
 * Tests for findElement() and findElements() method of RemoteWebDriver.
 * @covers \Facebook\WebDriver\Remote\RemoteWebDriver
 */
class RemoteWebDriverFindElementTest extends WebDriverTestCase
{
    public function testShouldThrowExceptionIfElementCannotBeFound()
    {
        $this->driver->get($this->getTestPageUrl('index.html'));

        $this->expectException(NoSuchElementException::class);
        $this->driver->findElement(WebDriverBy::id('not_existing'));
    }

    public function testShouldFindElementIfExistsOnAPage()
    {
        $this->driver->get($this->getTestPageUrl('index.html'));

        $element = $this->driver->findElement(WebDriverBy::id('id_test'));

        $this->assertInstanceOf(RemoteWebElement::class, $element);
    }

    public function testShouldReturnEmptyArrayIfElementsCannotBeFound()
    {
        $this->driver->get($this->getTestPageUrl('index.html'));

        $elements = $this->driver->findElements(WebDriverBy::cssSelector('not_existing'));

        $this->assertInternalType('array', $elements);
        $this->assertCount(0, $elements);
    }

    public function testShouldFindMultipleElements()
    {
        $this->driver->get($this->getTestPageUrl('index.html'));

        $elements = $this->driver->findElements(WebDriverBy::cssSelector('ul > li'));

        $this->assertInternalType('array', $elements);
        $this->assertCount(5, $elements);
        $this->assertContainsOnlyInstancesOf(RemoteWebElement::class, $elements);
    }
}
