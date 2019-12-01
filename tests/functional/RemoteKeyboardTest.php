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
 * @covers  \Facebook\WebDriver\Remote\RemoteKeyboard
 */
class RemoteKeyboardTest extends WebDriverTestCase
{
    /**
     * @group exclude-firefox
     * Firefox does not properly support keyboard actions:
     * https://github.com/mozilla/geckodriver/issues/245
     * https://github.com/mozilla/geckodriver/issues/646
     * https://github.com/mozilla/geckodriver/issues/944
     * @group exclude-edge
     */
    public function testShouldPressSendAndReleaseKeys()
    {
        if ($this->desiredCapabilities->getBrowserName() === WebDriverBrowserType::HTMLUNIT) {
            $this->markTestSkipped('Not peorperly supported by HtmlUnit browser');
        }

        $this->driver->get($this->getTestPageUrl('events.html'));

        $this->driver->getKeyboard()->sendKeys('ab');
        $this->driver->getKeyboard()->pressKey(WebDriverKeys::SHIFT);

        $this->driver->getKeyboard()->sendKeys('cd' . WebDriverKeys::NULL . 'e');

        $this->driver->getKeyboard()->pressKey(WebDriverKeys::SHIFT);
        $this->driver->getKeyboard()->pressKey('f');
        $this->driver->getKeyboard()->releaseKey(WebDriverKeys::SHIFT);
        $this->driver->getKeyboard()->releaseKey('f');

        if (self::isW3cProtocolBuild()) {
            $this->assertEquals(
                [
                    'keydown "a"',
                    'keyup "a"',
                    'keydown "b"',
                    'keyup "b"',
                    'keydown "Shift"',
                    'keydown "C"',
                    'keyup "C"',
                    'keydown "D"',
                    'keyup "D"',
                    'keyup "Shift"',
                    'keydown "e"',
                    'keyup "e"',
                    'keydown "Shift"',
                    'keydown "F"',
                    'keyup "Shift"',
                    'keyup "f"',
                ],
                $this->retrieveLoggedEvents()
            );
        } else {
            $this->assertEquals(
                [
                    'keydown "a"',
                    'keyup "a"',
                    'keydown "b"',
                    'keyup "b"',
                    'keydown "Shift"',
                    'keydown "C"',
                    'keyup "C"',
                    'keydown "D"',
                    'keyup "D"',
                    'keyup "Shift"',
                    'keydown "e"',
                    'keyup "e"',
                    'keydown "Shift"',
                    'keydown "F"', // pressKey behaves differently on old protocol
                    'keyup "F"',
                    'keyup "Shift"',
                    'keydown "f"',
                    'keyup "f"',
                ],
                $this->retrieveLoggedEvents()
            );
        }
    }

    /**
     * @return array
     */
    private function retrieveLoggedEvents()
    {
        $logElement = $this->driver->findElement(WebDriverBy::id('keyboardEventsLog'));

        return explode("\n", $logElement->getText());
    }
}
