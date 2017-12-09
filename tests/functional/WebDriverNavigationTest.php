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
 * @coversDefaultClass \Facebook\WebDriver\WebDriverNavigation
 */
class WebDriverNavigationTest extends WebDriverTestCase
{
    /**
     * @covers ::to
     * @covers ::__construct
     */
    public function testShouldNavigateToUrl()
    {
        $this->driver->navigate()->to($this->getTestPageUrl('index.html'));

        $this->assertStringEndsWith('/index.html', $this->driver->getCurrentURL());
    }

    /**
     * @covers ::back
     * @covers ::forward
     */
    public function testShouldNavigateBackAndForward()
    {
        $this->driver->get($this->getTestPageUrl('index.html'));
        $linkElement = $this->driver->findElement(WebDriverBy::id('a-form'));

        $linkElement->click();

        $this->driver->wait()->until(
            WebDriverExpectedCondition::urlContains('form.html')
        );

        $this->driver->navigate()->back();

        $this->driver->wait()->until(
            WebDriverExpectedCondition::urlContains('index.html')
        );

        $this->driver->navigate()->forward();

        $this->driver->wait()->until(
            WebDriverExpectedCondition::urlContains('form.html')
        );
    }

    /**
     * @covers ::refresh
     */
    public function testShouldRefreshPage()
    {
        $this->driver->get($this->getTestPageUrl('index.html'));

        // Change input element content, to make sure it was refreshed (=> cleared to original value)
        $inputElement = $this->driver->findElement(WebDriverBy::name('test_name'));
        $inputElementOriginalValue = $inputElement->getAttribute('value');
        $inputElement->clear()->sendKeys('New value');
        $this->assertSame('New value', $inputElement->getAttribute('value'));

        $this->driver->navigate()->refresh();

        $this->driver->wait()->until(
            WebDriverExpectedCondition::stalenessOf($inputElement)
        );

        $inputElementAfterRefresh = $this->driver->findElement(WebDriverBy::name('test_name'));

        $this->assertSame($inputElementOriginalValue, $inputElementAfterRefresh->getAttribute('value'));
    }
}
