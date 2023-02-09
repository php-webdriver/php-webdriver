<?php declare(strict_types=1);

namespace Facebook\WebDriver;

/**
 * @covers  \Facebook\WebDriver\Remote\RemoteKeyboard
 */
class RemoteKeyboardTest extends WebDriverTestCase
{
    use RetrieveEventsTrait;

    /**
     * @group exclude-edge
     * @group exclude-firefox
     * Firefox does not properly support keyboard actions:
     * https://github.com/mozilla/geckodriver/issues/245
     * https://github.com/mozilla/geckodriver/issues/646
     * https://github.com/mozilla/geckodriver/issues/944
     * @group exclude-safari
     * https://feedbackassistant.apple.com/feedback/9051272
     */
    public function testShouldPressSendAndReleaseKeys(): void
    {
        $this->driver->get($this->getTestPageUrl(TestPage::EVENTS));

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
                $this->retrieveLoggedKeyboardEvents()
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
                $this->retrieveLoggedKeyboardEvents()
            );
        }
    }
}
