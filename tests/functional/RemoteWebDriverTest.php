<?php declare(strict_types=1);

namespace Facebook\WebDriver;

use Facebook\WebDriver\Remote\HttpCommandExecutor;
use Facebook\WebDriver\Remote\RemoteWebDriver;

/**
 * @coversDefaultClass \Facebook\WebDriver\Remote\RemoteWebDriver
 */
class RemoteWebDriverTest extends WebDriverTestCase
{
    /**
     * @covers ::getTitle
     */
    public function testShouldGetPageTitle(): void
    {
        $this->driver->get($this->getTestPageUrl(TestPage::INDEX));

        $this->assertEquals(
            'php-webdriver test page',
            $this->driver->getTitle()
        );
    }

    /**
     * @covers ::get
     * @covers ::getCurrentURL
     */
    public function testShouldGetCurrentUrl(): void
    {
        $this->driver->get($this->getTestPageUrl(TestPage::INDEX));

        $this->assertStringEndsWith('/index.html', $this->driver->getCurrentURL());
    }

    /**
     * @covers ::getPageSource
     */
    public function testShouldGetPageSource(): void
    {
        $this->driver->get($this->getTestPageUrl(TestPage::INDEX));

        $source = $this->driver->getPageSource();
        $this->assertStringContainsString('<h1 id="welcome">', $source);
        $this->assertStringContainsString('Welcome to the php-webdriver testing page.', $source);
    }

    /**
     * @covers ::getSessionID
     * @covers ::isW3cCompliant
     */
    public function testShouldGetSessionId(): void
    {
        // This tests is intentionally included in another test, to not slow down build.
        // @TODO Remove following in 2.0
        if (self::isW3cProtocolBuild()) {
            $this->assertTrue($this->driver->isW3cCompliant());
        } else {
            $this->assertFalse($this->driver->isW3cCompliant());
        }

        $sessionId = $this->driver->getSessionID();

        $this->assertIsString($sessionId);
        $this->assertNotEmpty($sessionId);
    }

    /**
     * @group exclude-saucelabs
     * @covers ::getAllSessions
     */
    public function testShouldGetAllSessions(): void
    {
        self::skipForW3cProtocol();

        $sessions = RemoteWebDriver::getAllSessions($this->serverUrl, 30000);

        $this->assertIsArray($sessions);
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
    public function testShouldQuitAndUnsetExecutor(): void
    {
        self::skipForW3cProtocol();

        $this->assertCount(
            1,
            RemoteWebDriver::getAllSessions($this->serverUrl)
        );
        $this->assertInstanceOf(HttpCommandExecutor::class, $this->driver->getCommandExecutor());

        $this->driver->quit();

        // Wait a while until chromedriver finishes deleting the session.
        // https://bugs.chromium.org/p/chromedriver/issues/detail?id=3736
        usleep(250000); // 250 ms

        $this->assertCount(
            0,
            RemoteWebDriver::getAllSessions($this->serverUrl)
        );
        $this->assertNull($this->driver->getCommandExecutor());
    }

    /**
     * @covers ::getWindowHandle
     * @covers ::getWindowHandles
     */
    public function testShouldGetWindowHandles(): void
    {
        $this->driver->get($this->getTestPageUrl(TestPage::OPEN_NEW_WINDOW));

        $windowHandle = $this->driver->getWindowHandle();
        $windowHandles = $this->driver->getWindowHandles();

        $this->assertIsString($windowHandle);
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
    public function testShouldCloseWindow(): void
    {
        $this->driver->get($this->getTestPageUrl(TestPage::OPEN_NEW_WINDOW));
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
    public function testShouldExecuteScriptAndDoNotBlockExecution(): void
    {
        $this->driver->get($this->getTestPageUrl(TestPage::INDEX));

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
    public function testShouldExecuteAsyncScriptAndWaitUntilItIsFinished(): void
    {
        $this->driver->manage()->timeouts()->setScriptTimeout(1);

        $this->driver->get($this->getTestPageUrl(TestPage::INDEX));

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
    public function testShouldExecuteScriptWithParamsAndReturnValue(): void
    {
        $this->driver->manage()->timeouts()->setScriptTimeout(1);

        $this->driver->get($this->getTestPageUrl(TestPage::INDEX));

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
     * @covers \Facebook\WebDriver\Support\ScreenshotHelper
     */
    public function testShouldTakeScreenshot(): void
    {
        if (!extension_loaded('gd')) {
            $this->markTestSkipped('GD extension must be enabled');
        }
        $this->driver->get($this->getTestPageUrl(TestPage::INDEX));

        $outputPng = $this->driver->takeScreenshot();

        $image = imagecreatefromstring($outputPng);
        $this->assertNotFalse($image);

        $this->assertGreaterThan(0, imagesx($image));
        $this->assertGreaterThan(0, imagesy($image));
    }

    /**
     * @covers ::takeScreenshot
     * @covers \Facebook\WebDriver\Support\ScreenshotHelper
     * @group exclude-safari
     *        Safari is returning different color profile and it does not have way to configure "force-color-profile"
     */
    public function testShouldSaveScreenshotToFile(): void
    {
        if (!extension_loaded('gd')) {
            $this->markTestSkipped('GD extension must be enabled');
        }

        $screenshotPath = sys_get_temp_dir() . '/' . uniqid('php-webdriver-') . '/selenium-screenshot.png';

        $this->driver->get($this->getTestPageUrl(TestPage::INDEX));

        $this->driver->takeScreenshot($screenshotPath);

        $image = imagecreatefrompng($screenshotPath);
        $this->assertNotFalse($image);

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
    public function testShouldGetRemoteEndStatus(): void
    {
        $status = $this->driver->getStatus();

        $this->assertIsBool($status->isReady());
        $this->assertIsArray($status->getMeta());

        if (getenv('BROWSER_NAME') !== 'safari') {
            $this->assertNotEmpty($status->getMessage());
        }
    }
}
