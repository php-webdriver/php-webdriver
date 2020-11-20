<?php

namespace Facebook\WebDriver\Chrome;

use Facebook\WebDriver\Remote\WebDriverBrowserType;
use Facebook\WebDriver\WebDriverTestCase;

/**
 * @group exclude-saucelabs
 * @group exclude-firefox
 * @group exclude-edge
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

    public function testShouldExecuteDevToolsCommandWithoutParameters()
    {
        $devTools = new ChromeDevToolsDriver($this->driver);

        $result = $devTools->execute('Performance.enable');

        $this->assertSame([], $result);
    }

    public function testShouldExecuteDevToolsCommandWithParameters()
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
