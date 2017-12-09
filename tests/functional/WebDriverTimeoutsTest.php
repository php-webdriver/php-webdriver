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
use Facebook\WebDriver\Exception\ScriptTimeoutException;
use Facebook\WebDriver\Exception\TimeOutException;
use Facebook\WebDriver\Remote\RemoteWebElement;
use Facebook\WebDriver\Remote\WebDriverBrowserType;

/**
 * @coversDefaultClass \Facebook\WebDriver\WebDriverTimeouts
 */
class WebDriverTimeoutsTest extends WebDriverTestCase
{
    public function testShouldFailGettingDelayedElementWithoutWait()
    {
        $this->driver->get($this->getTestPageUrl('delayed_element.html'));

        $this->expectException(NoSuchElementException::class);
        $this->driver->findElement(WebDriverBy::id('delayed'));
    }

    /**
     * @covers ::implicitlyWait
     * @covers ::__construct
     */
    public function testShouldGetDelayedElementWithImplicitWait()
    {
        $this->driver->get($this->getTestPageUrl('delayed_element.html'));

        $this->driver->manage()->timeouts()->implicitlyWait(1);
        $element = $this->driver->findElement(WebDriverBy::id('delayed'));

        $this->assertInstanceOf(RemoteWebElement::class, $element);
    }

    /**
     * @covers ::pageLoadTimeout
     * @covers ::__construct
     */
    public function testShouldFailIfPageIsLoadingLongerThanPageLoadTimeout()
    {
        if ($this->desiredCapabilities->getBrowserName() == WebDriverBrowserType::HTMLUNIT) {
            $this->markTestSkipped('Not supported by HtmlUnit browser');
        }

        $this->driver->manage()->timeouts()->pageLoadTimeout(1);

        try {
            $this->driver->get($this->getTestPageUrl('slow_loading.html'));
            $this->fail('ScriptTimeoutException or TimeOutException exception should be thrown');
        } catch (TimeOutException $e) { // thrown by Selenium 3.0.0+
        } catch (ScriptTimeoutException $e) { // thrown by Selenium 2
        }
    }
}
