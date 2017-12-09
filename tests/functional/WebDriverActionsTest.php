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

use Facebook\WebDriver\Remote\WebDriverBrowserType;

/**
 * @coversDefaultClass \Facebook\WebDriver\Interactions\WebDriverActions
 */
class WebDriverActionsTest extends WebDriverTestCase
{
    protected function setUp()
    {
        parent::setUp();

        $this->driver->get($this->getTestPageUrl('events.html'));
    }

    /**
     * @covers ::__construct
     * @covers ::click
     * @covers ::perform
     */
    public function testShouldClickOnElement()
    {
        if ($this->desiredCapabilities->getBrowserName() === WebDriverBrowserType::HTMLUNIT) {
            $this->markTestSkipped('Not supported by HtmlUnit browser');
        }

        $element = $this->driver->findElement(WebDriverBy::id('item-1'));

        $this->driver->action()
            ->click($element)
            ->perform();

        $this->assertSame(
            ['mouseover item-1', 'mousedown item-1', 'mouseup item-1', 'click item-1'],
            $this->retrieveLoggedEvents()
        );
    }

    /**
     * @covers ::__construct
     * @covers ::clickAndHold
     * @covers ::release
     * @covers ::perform
     */
    public function testShouldClickAndHoldOnElementAndRelease()
    {
        if ($this->desiredCapabilities->getBrowserName() === WebDriverBrowserType::HTMLUNIT) {
            $this->markTestSkipped('Not supported by HtmlUnit browser');
        }

        $element = $this->driver->findElement(WebDriverBy::id('item-1'));

        $this->driver->action()
            ->clickAndHold($element)
            ->release()
            ->perform();

        $this->assertSame(
            ['mouseover item-1', 'mousedown item-1', 'mouseup item-1', 'click item-1'],
            $this->retrieveLoggedEvents()
        );
    }

    /**
     * @covers ::__construct
     * @covers ::contextClick
     * @covers ::perform
     */
    public function testShouldContextClickOnElement()
    {
        if ($this->desiredCapabilities->getBrowserName() === WebDriverBrowserType::HTMLUNIT) {
            $this->markTestSkipped('Not supported by HtmlUnit browser');
        }

        if ($this->desiredCapabilities->getBrowserName() === WebDriverBrowserType::MICROSOFT_EDGE) {
            $this->markTestSkipped('Getting stuck in EdgeDriver');
        }

        $element = $this->driver->findElement(WebDriverBy::id('item-2'));

        $this->driver->action()
            ->contextClick($element)
            ->perform();

        $loggedEvents = $this->retrieveLoggedEvents();

        $this->assertContains('mousedown item-2', $loggedEvents);
        $this->assertContains('mouseup item-2', $loggedEvents);
        $this->assertContains('contextmenu item-2', $loggedEvents);
    }

    /**
     * @covers ::__construct
     * @covers ::doubleClick
     * @covers ::perform
     */
    public function testShouldDoubleClickOnElement()
    {
        if ($this->desiredCapabilities->getBrowserName() === WebDriverBrowserType::HTMLUNIT) {
            $this->markTestSkipped('Not supported by HtmlUnit browser');
        }

        $element = $this->driver->findElement(WebDriverBy::id('item-3'));

        $this->driver->action()
            ->doubleClick($element)
            ->perform();

        $this->assertSame(
            ['mouseover item-3', 'mousedown item-3', 'mouseup item-3', 'click item-3', 'dblclick item-3'],
            $this->retrieveLoggedEvents()
        );
    }

    /**
     * @return array
     */
    private function retrieveLoggedEvents()
    {
        $logElement = $this->driver->findElement(WebDriverBy::id('log'));

        return explode("\n", $logElement->getText());
    }
}
