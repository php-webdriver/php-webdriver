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

use Facebook\WebDriver\Exception\NoAlertOpenException;
use Facebook\WebDriver\Remote\WebDriverBrowserType;

/**
 * @covers \Facebook\WebDriver\WebDriverAlert
 */
class WebDriverAlertTest extends WebDriverTestCase
{
    protected function setUp()
    {
        if (getenv('CHROME_HEADLESS') === '1') {
            // Alerts in headless mode should be available in next Chrome version (61), see:
            // https://bugs.chromium.org/p/chromium/issues/detail?id=718235
            $this->markTestSkipped('Alerts not yet supported by headless Chrome');
        }

        parent::setUp();

        $this->driver->get($this->getTestPageUrl('alert.html'));
    }

    public function testShouldAcceptAlert()
    {
        // Open alert
        $this->driver->findElement(WebDriverBy::id('open-alert'))->click();

        // Wait until present
        $this->driver->wait()->until(WebDriverExpectedCondition::alertIsPresent());

        $this->assertSame('This is alert', $this->driver->switchTo()->alert()->getText());

        $this->driver->switchTo()->alert()->accept();

        $this->expectException(NoAlertOpenException::class);
        $this->driver->switchTo()->alert()->accept();
    }

    public function testShouldAcceptAndDismissConfirmation()
    {
        if ($this->desiredCapabilities->getBrowserName() == WebDriverBrowserType::HTMLUNIT) {
            /** @see https://github.com/SeleniumHQ/htmlunit-driver/issues/14 */
            $this->markTestSkipped('Not supported by HtmlUnit browser');
        }

        // Open confirmation
        $this->driver->findElement(WebDriverBy::id('open-confirm'))->click();

        // Wait until present
        $this->driver->wait()->until(WebDriverExpectedCondition::alertIsPresent());

        $this->assertSame('Do you confirm?', $this->driver->switchTo()->alert()->getText());

        // Test accepting
        $this->driver->switchTo()->alert()->accept();
        $this->assertSame('accepted', $this->getResultText());

        // Open confirmation
        $this->driver->findElement(WebDriverBy::id('open-confirm'))->click();

        // Test dismissal
        $this->driver->switchTo()->alert()->dismiss();
        $this->assertSame('dismissed', $this->getResultText());
    }

    public function testShouldSubmitPromptText()
    {
        if ($this->desiredCapabilities->getBrowserName() == WebDriverBrowserType::HTMLUNIT) {
            /** @see https://github.com/SeleniumHQ/htmlunit-driver/issues/14 */
            $this->markTestSkipped('Not supported by HtmlUnit browser');
        }

        // Open confirmation
        $this->driver->findElement(WebDriverBy::id('open-prompt'))->click();

        // Wait until present
        $this->driver->wait()->until(WebDriverExpectedCondition::alertIsPresent());

        $this->assertSame('Enter prompt value', $this->driver->switchTo()->alert()->getText());

        $this->driver->switchTo()->alert()->sendKeys('Text entered to prompt');
        $this->driver->switchTo()->alert()->accept();

        $this->assertSame('Text entered to prompt', $this->getResultText());
    }

    private function getResultText()
    {
        return $this->driver
            ->findElement(WebDriverBy::id('result'))
            ->getText();
    }
}
