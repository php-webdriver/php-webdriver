<?php declare(strict_types=1);

namespace Facebook\WebDriver\Chrome;

use Facebook\WebDriver\Remote\WebDriverBrowserType;
use Facebook\WebDriver\WebDriverTestCase;

/**
 * @group exclude-edge
 * @group exclude-firefox
 * @group exclude-safari
 * @group exclude-saucelabs
 */
class ChromeDevToolsDriverTest extends WebDriverTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        if ($this->desiredCapabilities->getBrowserName() !== WebDriverBrowserType::CHROME) {
            $this->markTestSkipped('ChromeDevTools are available only in Chrome');
        }
    }

    public function testShouldExecuteDevToolsCommandWithoutParameters(): void
    {
        $devTools = new ChromeDevToolsDriver($this->driver);

        $result = $devTools->execute('Performance.enable');

        $this->assertSame([], $result);
    }

    public function testShouldExecuteDevToolsCommandWithParameters(): void
    {
        $devTools = new ChromeDevToolsDriver($this->driver);

        $result = $devTools->execute('Runtime.evaluate', [
            'returnByValue' => true,
            'expression' => '42 + 1',
        ]);

        $this->assertSame('number', $result['result']['type']);
        $this->assertSame(43, $result['result']['value']);
    }
}
