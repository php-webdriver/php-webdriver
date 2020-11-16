<?php

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
     * @covers ::get
     * @covers ::getCurrentURL
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
        $this->compatAssertStringContainsString('<h1 id="welcome">', $source);
        $this->compatAssertStringContainsString('Welcome to the php-webdriver testing page.', $source);
    }

    /**
     * @covers ::getSessionID
     * @covers ::isW3cCompliant
     */
    public function testShouldGetSessionId()
    {
        // This tests is intentionally included in another test, to not slow down build.
        // @TODO Remove following in 2.0
        if (self::isW3cProtocolBuild()) {
            $this->assertTrue($this->driver->isW3cCompliant());
        } else {
            $this->assertFalse($this->driver->isW3cCompliant());
        }

        $sessionId = $this->driver->getSessionID();

        $this->assertTrue(is_string($sessionId));
        $this->assertNotEmpty($sessionId);
    }

    /**
     * @group exclude-saucelabs
     * @covers ::getAllSessions
     */
    public function testShouldGetAllSessions()
    {
        if (getenv('GECKODRIVER') === '1') {
            $this->markTestSkipped('"getAllSessions" is not supported by the W3C specification');
        }

        $sessions = RemoteWebDriver::getAllSessions($this->serverUrl, 30000);

        $this->assertTrue(is_array($sessions));
        $this->assertCount(1, $sessions);

        $this->assertArrayHasKey('capabilities', $sessions[0]);
        $this->assertArrayHasKey('id', $sessions[0]);
    }

    /**
     * @group exclude-saucelabs
     * @covers ::getAllSessions
     * @covers ::getCommandExecutor
     * @covers ::quit
     */
    public function testShouldQuitAndUnsetExecutor()
    {
        if (getenv('GECKODRIVER') === '1') {
            $this->markTestSkipped('"getAllSessions" is not supported by the W3C specification');
        }

        $this->assertCount(
            1,
            RemoteWebDriver::getAllSessions($this->serverUrl, 30000)
        );
        $this->assertInstanceOf(HttpCommandExecutor::class, $this->driver->getCommandExecutor());

        $this->driver->quit();

        $this->assertCount(
            0,
            RemoteWebDriver::getAllSessions($this->serverUrl, 30000)
        );
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

        $this->assertTrue(is_string($windowHandle));
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

        $this->driver->wait()->until(WebDriverExpectedCondition::numberOfWindowsToBe(2));

        $this->assertCount(2, $this->driver->getWindowHandles());

        $this->driver->close();

        $this->assertCount(1, $this->driver->getWindowHandles());
    }

    /**
     * @covers ::executeScript
     * @group exclude-saucelabs
     */
    public function testShouldExecuteScriptAndDoNotBlockExecution()
    {
        $this->driver->get($this->getTestPageUrl('index.html'));

        $element = $this->driver->findElement(WebDriverBy::id('id_test'));
        $this->assertSame('Test by ID', $element->getText());

        $start = microtime(true);
        $scriptResult = $this->driver->executeScript('
            setTimeout(
                function(){document.getElementById("id_test").innerHTML = "Text changed by script";},
                250
            );
            return "returned value";
            ');
        $end = microtime(true);

        $this->assertSame('returned value', $scriptResult);

        $this->assertLessThan(250, $end - $start, 'executeScript() should not block execution');

        // If we wait, the script should be executed and its value changed
        usleep(300000); // wait 300 ms
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

        $start = microtime(true);
        $scriptResult = $this->driver->executeAsyncScript(
            'var callback = arguments[arguments.length - 1];
            setTimeout(
                function(){
                    document.getElementById("id_test").innerHTML = "Text changed by script";
                    callback("returned value");
                 },
                250
            );'
        );
        $end = microtime(true);

        $this->assertSame('returned value', $scriptResult);

        $this->assertGreaterThan(
            0.250,
            $end - $start,
            'executeAsyncScript() should block execution until callback() is called'
        );

        // The result must be immediately available, as the executeAsyncScript should block the execution until the
        // callback is called.
        $this->assertSame('Text changed by script', $element->getText());
    }

    /**
     * @covers ::executeScript
     * @covers ::prepareScriptArguments
     * @group exclude-saucelabs
     */
    public function testShouldExecuteScriptWithParamsAndReturnValue()
    {
        $this->driver->manage()->timeouts()->setScriptTimeout(1);

        $this->driver->get($this->getTestPageUrl('index.html'));

        $element1 = $this->driver->findElement(WebDriverBy::id('id_test'));
        $element2 = $this->driver->findElement(WebDriverBy::className('test_class'));

        $scriptResult = $this->driver->executeScript(
            'var element1 = arguments[0];
            var element2 = arguments[1];
            return "1: " + element1.innerText + ", 2: " + element2.innerText;
            ',
            [$element1, $element2]
        );

        $this->assertSame('1: Test by ID, 2: Test by Class', $scriptResult);
    }

    /**
     * @covers ::takeScreenshot
     */
    public function testShouldTakeScreenshot()
    {
        if (!extension_loaded('gd')) {
            $this->markTestSkipped('GD extension must be enabled');
        }
        if ($this->desiredCapabilities->getBrowserName() === WebDriverBrowserType::HTMLUNIT) {
            $this->markTestSkipped('Screenshots are not supported by HtmlUnit browser');
        }

        $this->driver->get($this->getTestPageUrl('index.html'));

        $outputPng = $this->driver->takeScreenshot();

        $image = imagecreatefromstring($outputPng);
        $this->assertTrue(is_resource($image));

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
        if ($this->desiredCapabilities->getBrowserName() === WebDriverBrowserType::HTMLUNIT) {
            $this->markTestSkipped('Screenshots are not supported by HtmlUnit browser');
        }

        // Intentionally save screenshot to subdirectory to tests it is being created
        $screenshotPath = sys_get_temp_dir() . '/' . uniqid('php-webdriver-') . '/selenium-screenshot.png';

        $this->driver->get($this->getTestPageUrl('index.html'));

        $this->driver->takeScreenshot($screenshotPath);

        $image = imagecreatefrompng($screenshotPath);
        $this->assertTrue(is_resource($image));

        $this->assertGreaterThan(0, imagesx($image));
        $this->assertGreaterThan(0, imagesy($image));

        // Validate expected red box is present on the screenshot
        $this->assertSame(
            ['red' => 255, 'green' => 0, 'blue' => 0, 'alpha' => 0],
            imagecolorsforindex($image, imagecolorat($image, 5, 5))
        );

        // And whitespace has expected background color
        $this->assertSame(
            ['red' => 250, 'green' => 250, 'blue' => 255, 'alpha' => 0],
            imagecolorsforindex($image, imagecolorat($image, 15, 5))
        );

        unlink($screenshotPath);
        rmdir(dirname($screenshotPath));
    }

    /**
     * @covers ::getStatus
     * @covers \Facebook\WebDriver\Remote\RemoteStatus
     * @group exclude-saucelabs
     * Status endpoint is not supported on Sauce Labs
     */
    public function testShouldGetRemoteEndStatus()
    {
        $status = $this->driver->getStatus();

        $this->assertTrue(is_bool($status->isReady()));
        $this->assertNotEmpty($status->getMessage());

        $this->assertTrue(is_array($status->getMeta()));
    }
}
