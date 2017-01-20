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

/**
 * @coversDefaultClass Facebook\WebDriver\Remote\RemoteWebElement
 */
class RemoteWebElementTest extends WebDriverTestCase
{
    /**
     * @covers ::getText
     */
    public function testShouldGetText()
    {
        $this->driver->get($this->getTestPath('index.html'));
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
        $this->driver->get($this->getTestPath('index.html'));

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
        $this->driver->get($this->getTestPath('index.html'));

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
        $this->driver->get($this->getTestPath('index.html'));

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
        $this->driver->get($this->getTestPath('index.html'));

        $elementWithBorder = $this->driver->findElement(WebDriverBy::id('text-simple'));
        $elementWithoutBorder = $this->driver->findElement(WebDriverBy::id('text-with-spaces'));

        $this->assertSame('solid', $elementWithBorder->getCSSValue('border-left-style'));
        $this->assertSame('none', $elementWithoutBorder->getCSSValue('border-left-style'));

        $this->assertSame('rgba(0, 0, 0, 1)', $elementWithBorder->getCSSValue('border-left-color'));
        $this->assertSame('rgba(0, 0, 0, 1)', $elementWithoutBorder->getCSSValue('border-left-color'));
    }
}
