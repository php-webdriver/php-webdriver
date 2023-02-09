<?php declare(strict_types=1);

namespace Facebook\WebDriver\Firefox;

use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\TestPage;
use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\WebDriverExpectedCondition;
use Facebook\WebDriver\WebDriverTestCase;
use PHPUnit\Framework\TestCase;

/**
 * ## Generating/updating the Firefox extension fixture ##
 *
 * For testing purposes, we use dummy Firefox extension (`Fixtures/FirefoxExtension.xpi`), which adds `<div>` element
 * with some text at the end of each page Firefox renders.
 *
 * In case the extension will need to be modified, steps below must be followed,
 * otherwise firefox won't load the modified extension:
 *
 * - Extract the xpi file (it is a zip archive) to some temporary directory
 * - Make needed changes in the files
 * - Install web-ext tool from Mozilla (@see https://github.com/mozilla/web-ext)
 * - Sign in to https://addons.mozilla.org/cs/developers/addon/api/key/ to get your JWT API key and JWT secret
 * - Run `web-ext sign --channel=unlisted --api-key=[you-api-key] --api-secret=[your-api-secret]` in the extension dir
 * - Store the output file (`web-ext-artifacts/[...].xpi`) to the Fixtures/ directory
 *
 * @group exclude-saucelabs
 * @covers \Facebook\WebDriver\Firefox\FirefoxProfile
 */
class FirefoxProfileTest extends TestCase
{
    /** @var FirefoxDriver */
    protected $driver;

    protected $firefoxTestExtensionFilename = __DIR__ . '/Fixtures/FirefoxExtension.xpi';

    protected function setUp(): void
    {
        if (getenv('BROWSER_NAME') !== 'firefox' || empty(getenv('GECKODRIVER_PATH'))
            || WebDriverTestCase::isSauceLabsBuild()) {
            $this->markTestSkipped('The test is run only when running against local firefox');
        }
    }

    protected function tearDown(): void
    {
        if ($this->driver instanceof RemoteWebDriver && $this->driver->getCommandExecutor() !== null) {
            $this->driver->quit();
        }
    }

    public function testShouldStartDriverWithEmptyProfile(): void
    {
        $firefoxProfile = new FirefoxProfile();
        $this->startFirefoxDriverWithProfile($firefoxProfile);

        $this->driver->get('http://localhost:8000/');
        $element = $this->driver->findElement(WebDriverBy::id('welcome'));
        $this->assertSame(
            'Welcome to the php-webdriver testing page.',
            $element->getText()
        );
    }

    public function testShouldInstallExtension(): void
    {
        $firefoxProfile = new FirefoxProfile();
        $firefoxProfile->addExtension($this->firefoxTestExtensionFilename);
        $this->startFirefoxDriverWithProfile($firefoxProfile);

        $this->driver->get($this->getTestPageUrl(TestPage::INDEX));

        $this->assertInstanceOf(RemoteWebDriver::class, $this->driver);

        // it sometimes takes split of a second for the extension to render the element, so we must use wait
        $element = $this->driver->wait(5, 1)->until(
            WebDriverExpectedCondition::presenceOfElementLocated(WebDriverBy::id('webDriverExtensionTest'))
        );

        $this->assertEquals('This element was added by browser extension', $element->getText());
    }

    public function testShouldUseProfilePreferences(): void
    {
        $firefoxProfile = new FirefoxProfile();

        // Please note, although it is possible to set preferences right into the profile (what this test does),
        // we recommend using the setPreference() method on FirefoxOptions instead, so that you don't need to
        // create FirefoxProfile.
        $firefoxProfile->setPreference('javascript.enabled', false);
        $this->assertSame('false', $firefoxProfile->getPreference('javascript.enabled'));

        $this->startFirefoxDriverWithProfile($firefoxProfile);
        $this->driver->get('http://localhost:8000/');

        $noScriptElement = $this->driver->findElement(WebDriverBy::id('noscript'));
        $this->assertEquals(
            'This element is only shown with JavaScript disabled.',
            $noScriptElement->getText()
        );
    }

    protected function getTestPageUrl($path): string
    {
        $host = 'http://localhost:8000';
        if ($alternateHost = getenv('FIXTURES_HOST')) {
            $host = $alternateHost;
        }

        return $host . '/' . $path;
    }

    private function startFirefoxDriverWithProfile(FirefoxProfile $firefoxProfile): void
    {
        // The createDefaultService() method expect path to the executable to be present in the environment variable
        putenv(FirefoxDriverService::WEBDRIVER_FIREFOX_DRIVER . '=' . getenv('GECKODRIVER_PATH'));

        $firefoxOptions = new FirefoxOptions();
        $firefoxOptions->addArguments(['-headless']);
        $firefoxOptions->setProfile($firefoxProfile);
        $desiredCapabilities = DesiredCapabilities::firefox();
        $desiredCapabilities->setCapability(FirefoxOptions::CAPABILITY, $firefoxOptions);

        $this->driver = FirefoxDriver::start($desiredCapabilities);
    }
}
