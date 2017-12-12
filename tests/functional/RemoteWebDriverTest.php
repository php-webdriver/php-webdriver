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

use Facebook\WebDriver\Remote\HttpCommandExecutor;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\Remote\WebDriverBrowserType;

/**
 * @coversDefaultClass \Facebook\WebDriver\Remote\RemoteWebDriver
 */
class RemoteWebDriverTest extends WebDriverTestCase
{
    /**
     * @covers ::getTitle
     */
    public function testShouldGetPageTitle()
    {
        $this->driver->get($this->getTestPageUrl('index.html'));

        $this->assertEquals(
            'php-webdriver test page',
            $this->driver->getTitle()
        );
    }

    /**
     * @covers ::getCurrentURL
     * @covers ::get
     */
    public function testShouldGetCurrentUrl()
    {
        $this->driver->get($this->getTestPageUrl('index.html'));

        $this->assertStringEndsWith('/index.html', $this->driver->getCurrentURL());
    }

    /**
     * @covers ::getPageSource
     */
    public function testShouldGetPageSource()
    {
        $this->driver->get($this->getTestPageUrl('index.html'));

        $source = $this->driver->getPageSource();
        $this->assertContains('<h1 id="welcome">', $source);
        $this->assertContains('Welcome to the facebook/php-webdriver testing page.', $source);
    }

    /**
     * @covers ::getSessionID
     */
    public function testShouldGetSessionId()
    {
        $sessionId = $this->driver->getSessionID();

        $this->assertInternalType('string', $sessionId);
        $this->assertNotEmpty($sessionId);
    }

    /**
     * @group exclude-saucelabs
     * @covers ::getAllSessions
     */
    public function testShouldGetAllSessions()
    {
        $sessions = RemoteWebDriver::getAllSessions($this->serverUrl);

        $this->assertInternalType('array', $sessions);
        $this->assertCount(1, $sessions);

        $this->assertArrayHasKey('capabilities', $sessions[0]);
        $this->assertArrayHasKey('id', $sessions[0]);
        $this->assertArrayHasKey('class', $sessions[0]);
    }

    /**
     * @group exclude-saucelabs
     * @covers ::getAllSessions
     * @covers ::getCommandExecutor
     * @covers ::quit
     */
    public function testShouldQuitAndUnsetExecutor()
    {
        $this->assertCount(1, RemoteWebDriver::getAllSessions($this->serverUrl));
        $this->assertInstanceOf(HttpCommandExecutor::class, $this->driver->getCommandExecutor());

        $this->driver->quit();

        $this->assertCount(0, RemoteWebDriver::getAllSessions($this->serverUrl));
        $this->assertNull($this->driver->getCommandExecutor());
    }

    /**
     * @covers ::getWindowHandle
     * @covers ::getWindowHandles
     */
    public function testShouldGetWindowHandles()
    {
        $this->driver->get($this->getTestPageUrl('open_new_window.html'));

        $windowHandle = $this->driver->getWindowHandle();
        $windowHandles = $this->driver->getWindowHandles();

        $this->assertInternalType('string', $windowHandle);
        $this->assertNotEmpty($windowHandle);
        $this->assertSame([$windowHandle], $windowHandles);

        // Open second window
        $this->driver->findElement(WebDriverBy::cssSelector('a'))->click();

        $this->driver->wait()->until(
            WebDriverExpectedCondition::numberOfWindowsToBe(2)
        );

        $this->assertCount(2, $this->driver->getWindowHandles());
    }

    /**
     * @covers ::close
     */
    public function testShouldCloseWindow()
    {
        $this->driver->get($this->getTestPageUrl('open_new_window.html'));
        $this->driver->findElement(WebDriverBy::cssSelector('a'))->click();

        $this->assertCount(2, $this->driver->getWindowHandles());

        $this->driver->close();

        $this->assertCount(1, $this->driver->getWindowHandles());
    }

    /**
     * @covers ::executeScript
     */
    public function testShouldExecuteScriptAndDoNotBlockExecution()
    {
        $this->driver->get($this->getTestPageUrl('index.html'));

        $element = $this->driver->findElement(WebDriverBy::id('id_test'));
        $this->assertSame('Test by ID', $element->getText());

        $this->driver->executeScript('
            setTimeout(
                function(){document.getElementById("id_test").innerHTML = "Text changed by script"},
                500
            )');

        // Make sure the script don't block the test execution
        $this->assertSame('Test by ID', $element->getText());

        // If we wait, the script should be executed
        usleep(1000000); // wait 1000 ms
        $this->assertSame('Text changed by script', $element->getText());
    }

    /**
     * @covers ::executeAsyncScript
     * @covers \Facebook\WebDriver\WebDriverTimeouts::setScriptTimeout
     */
    public function testShouldExecuteAsyncScriptAndWaitUntilItIsFinished()
    {
        $this->driver->manage()->timeouts()->setScriptTimeout(1);

        $this->driver->get($this->getTestPageUrl('index.html'));

        $element = $this->driver->findElement(WebDriverBy::id('id_test'));
        $this->assertSame('Test by ID', $element->getText());

        $this->driver->executeAsyncScript(
            'var callback = arguments[arguments.length - 1];
            setTimeout(
                function(){
                    document.getElementById("id_test").innerHTML = "Text changed by script";
                    callback();
                 },
                250
            );'
        );

        // The result must be immediately available, as the executeAsyncScript should block the execution until the
        // callback is called.
        $this->assertSame('Text changed by script', $element->getText());
    }

    /**
     * @covers ::takeScreenshot
     */
    public function testShouldTakeScreenshot()
    {
        if (!extension_loaded('gd')) {
            $this->markTestSkipped('GD extension must be enabled');
        }
        if ($this->desiredCapabilities->getBrowserName() == WebDriverBrowserType::HTMLUNIT) {
            $this->markTestSkipped('Screenshots are not supported by HtmlUnit browser');
        }

        $this->driver->get($this->getTestPageUrl('index.html'));

        $outputPng = $this->driver->takeScreenshot();

        $image = imagecreatefromstring($outputPng);
        $this->assertInternalType('resource', $image);

        $this->assertGreaterThan(0, imagesx($image));
        $this->assertGreaterThan(0, imagesy($image));
    }

    /**
     * @covers ::takeScreenshot
     */
    public function testShouldSaveScreenshotToFile()
    {
        if (!extension_loaded('gd')) {
            $this->markTestSkipped('GD extension must be enabled');
        }
        if ($this->desiredCapabilities->getBrowserName() == WebDriverBrowserType::HTMLUNIT) {
            $this->markTestSkipped('Screenshots are not supported by HtmlUnit browser');
        }

        $screenshotPath = sys_get_temp_dir() . '/selenium-screenshot.png';

        $this->driver->get($this->getTestPageUrl('index.html'));

        $this->driver->takeScreenshot($screenshotPath);

        $image = imagecreatefrompng($screenshotPath);
        $this->assertInternalType('resource', $image);

        $this->assertGreaterThan(0, imagesx($image));
        $this->assertGreaterThan(0, imagesy($image));

        unlink($screenshotPath);
    }
}
