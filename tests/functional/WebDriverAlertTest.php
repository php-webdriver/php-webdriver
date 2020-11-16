<?php

namespace Facebook\WebDriver;

use Facebook\WebDriver\Exception\NoAlertOpenException;
use Facebook\WebDriver\Exception\NoSuchAlertException;
use Facebook\WebDriver\Remote\WebDriverBrowserType;

/**
 * @covers \Facebook\WebDriver\Remote\RemoteTargetLocator
 * @covers \Facebook\WebDriver\WebDriverAlert
 */
class WebDriverAlertTest extends WebDriverTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->driver->get($this->getTestPageUrl('alert.html'));
    }

    public function testShouldAcceptAlert()
    {
        // Open alert (it is delayed for 1 second, to make sure following wait for alertIsPresent works properly)
        $this->driver->findElement(WebDriverBy::id('open-alert-delayed'))->click();

        // Wait until present
        $this->driver->wait()->until(WebDriverExpectedCondition::alertIsPresent());

        $this->assertSame('This is alert', $this->driver->switchTo()->alert()->getText());

        $this->driver->switchTo()->alert()->accept();

        if (self::isW3cProtocolBuild()) {
            $this->expectException(NoSuchAlertException::class);
        } else {
            $this->expectException(NoAlertOpenException::class);
        }

        $this->driver->switchTo()->alert()->accept();
    }

    public function testShouldAcceptAndDismissConfirmation()
    {
        if ($this->desiredCapabilities->getBrowserName() === WebDriverBrowserType::HTMLUNIT) {
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
        if ($this->desiredCapabilities->getBrowserName() === WebDriverBrowserType::HTMLUNIT) {
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
