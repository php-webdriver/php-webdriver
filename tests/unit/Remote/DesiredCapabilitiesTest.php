<?php declare(strict_types=1);

namespace Facebook\WebDriver\Remote;

use Facebook\WebDriver\Chrome\ChromeOptions;
use Facebook\WebDriver\Firefox\FirefoxDriver;
use Facebook\WebDriver\Firefox\FirefoxOptions;
use Facebook\WebDriver\Firefox\FirefoxOptionsTest;
use Facebook\WebDriver\Firefox\FirefoxProfile;
use Facebook\WebDriver\WebDriverPlatform;
use PHPUnit\Framework\TestCase;

class DesiredCapabilitiesTest extends TestCase
{
    public function testShouldInstantiateWithCapabilitiesGivenInConstructor(): void
    {
        $capabilities = new DesiredCapabilities(
            ['fooKey' => 'fooVal', WebDriverCapabilityType::PLATFORM => WebDriverPlatform::ANY]
        );

        $this->assertSame('fooVal', $capabilities->getCapability('fooKey'));
        $this->assertSame('ANY', $capabilities->getPlatform());

        $this->assertSame(
            ['fooKey' => 'fooVal', WebDriverCapabilityType::PLATFORM => WebDriverPlatform::ANY],
            $capabilities->toArray()
        );
    }

    public function testShouldInstantiateEmptyInstance(): void
    {
        $capabilities = new DesiredCapabilities();

        $this->assertNull($capabilities->getCapability('foo'));
        $this->assertSame([], $capabilities->toArray());
    }

    public function testShouldProvideAccessToCapabilitiesUsingSettersAndGetters(): void
    {
        $capabilities = new DesiredCapabilities();
        // generic capability setter
        $capabilities->setCapability('custom', 1337);
        // specific setters
        $capabilities->setBrowserName(WebDriverBrowserType::CHROME);
        $capabilities->setPlatform(WebDriverPlatform::LINUX);
        $capabilities->setVersion(333);

        $this->assertSame(1337, $capabilities->getCapability('custom'));
        $this->assertSame(WebDriverBrowserType::CHROME, $capabilities->getBrowserName());
        $this->assertSame(WebDriverPlatform::LINUX, $capabilities->getPlatform());
        $this->assertSame(333, $capabilities->getVersion());
    }

    public function testShouldAccessCapabilitiesIsser(): void
    {
        $capabilities = new DesiredCapabilities();

        $capabilities->setCapability('custom', 1337);
        $capabilities->setCapability('customBooleanTrue', true);
        $capabilities->setCapability('customBooleanFalse', false);
        $capabilities->setCapability('customNull', null);

        $this->assertTrue($capabilities->is('custom'));
        $this->assertTrue($capabilities->is('customBooleanTrue'));
        $this->assertFalse($capabilities->is('customBooleanFalse'));
        $this->assertFalse($capabilities->is('customNull'));
    }

    public function testShouldNotAllowToDisableJavascriptForNonHtmlUnitBrowser(): void
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('isJavascriptEnabled() is a htmlunit-only option');

        $capabilities = new DesiredCapabilities();
        $capabilities->setBrowserName(WebDriverBrowserType::FIREFOX);
        $capabilities->setJavascriptEnabled(false);
    }

    public function testShouldAllowToDisableJavascriptForHtmlUnitBrowser(): void
    {
        $capabilities = new DesiredCapabilities();
        $capabilities->setBrowserName(WebDriverBrowserType::HTMLUNIT);
        $capabilities->setJavascriptEnabled(false);

        $this->assertFalse($capabilities->isJavascriptEnabled());
    }

    /**
     * @dataProvider provideBrowserCapabilities
     */
    public function testShouldProvideShortcutSetupForCapabilitiesOfEachBrowser(
        string $setupMethod,
        string $expectedBrowser,
        string $expectedPlatform
    ): void {
        /** @var DesiredCapabilities $capabilities */
        $capabilities = call_user_func([DesiredCapabilities::class, $setupMethod]);

        $this->assertSame($expectedBrowser, $capabilities->getBrowserName());
        $this->assertSame($expectedPlatform, $capabilities->getPlatform());
    }

    /**
     * @return array[]
     */
    public function provideBrowserCapabilities(): array
    {
        return [
            ['android', WebDriverBrowserType::ANDROID, WebDriverPlatform::ANDROID],
            ['chrome', WebDriverBrowserType::CHROME, WebDriverPlatform::ANY],
            ['firefox', WebDriverBrowserType::FIREFOX, WebDriverPlatform::ANY],
            ['htmlUnit', WebDriverBrowserType::HTMLUNIT, WebDriverPlatform::ANY],
            ['htmlUnitWithJS', WebDriverBrowserType::HTMLUNIT, WebDriverPlatform::ANY],
            ['MicrosoftEdge', WebDriverBrowserType::MICROSOFT_EDGE, WebDriverPlatform::WINDOWS],
            ['internetExplorer', WebDriverBrowserType::IE, WebDriverPlatform::WINDOWS],
            ['iphone', WebDriverBrowserType::IPHONE, WebDriverPlatform::MAC],
            ['ipad', WebDriverBrowserType::IPAD, WebDriverPlatform::MAC],
            ['opera', WebDriverBrowserType::OPERA, WebDriverPlatform::ANY],
            ['safari', WebDriverBrowserType::SAFARI, WebDriverPlatform::ANY],
            ['phantomjs', WebDriverBrowserType::PHANTOMJS, WebDriverPlatform::ANY],
        ];
    }

    public function testShouldSetupFirefoxWithDefaultOptions(): void
    {
        $capabilitiesArray = DesiredCapabilities::firefox()->toArray();

        $this->assertSame('firefox', $capabilitiesArray['browserName']);
        $this->assertSame(
            [
                'prefs' => FirefoxOptionsTest::EXPECTED_DEFAULT_PREFS,
            ],
            $capabilitiesArray['moz:firefoxOptions']
        );
    }

    public function testShouldSetupFirefoxWithCustomOptions(): void
    {
        $firefoxOptions = new FirefoxOptions();
        $firefoxOptions->addArguments(['-headless']);
        $firefoxOptions->setOption('binary', '/foo/bar/firefox');

        $capabilities = DesiredCapabilities::firefox();
        $capabilities->setCapability(FirefoxOptions::CAPABILITY, $firefoxOptions);

        $capabilitiesArray = $capabilities->toArray();

        $this->assertSame('firefox', $capabilitiesArray['browserName']);
        $this->assertSame(
            [
                'binary' => '/foo/bar/firefox',
                'args' => ['-headless'],
                'prefs' => FirefoxOptionsTest::EXPECTED_DEFAULT_PREFS,
            ],
            $capabilitiesArray['moz:firefoxOptions']
        );
    }

    public function testShouldNotOverwriteDefaultFirefoxOptionsWhenAddingFirefoxOptionAsArray(): void
    {
        $capabilities = DesiredCapabilities::firefox();
        $capabilities->setCapability('moz:firefoxOptions', ['args' => ['-headless']]);

        $this->assertSame(
            [
                'prefs' => FirefoxOptionsTest::EXPECTED_DEFAULT_PREFS,
                'args' => ['-headless'],
            ],
            $capabilities->toArray()['moz:firefoxOptions']
        );
    }

    /**
     * @dataProvider provideW3cCapabilities
     */
    public function testShouldConvertCapabilitiesToW3cCompatible(
        DesiredCapabilities $inputJsonWireCapabilities,
        array $expectedW3cCapabilities
    ): void {
        $this->assertJsonStringEqualsJsonString(
            json_encode($expectedW3cCapabilities, JSON_THROW_ON_ERROR),
            json_encode($inputJsonWireCapabilities->toW3cCompatibleArray(), JSON_THROW_ON_ERROR)
        );
    }

    /**
     * @return array[]
     */
    public function provideW3cCapabilities(): array
    {
        $chromeOptions = new ChromeOptions();
        $chromeOptions->addArguments(['--headless']);

        $firefoxOptions = new FirefoxOptions();
        $firefoxOptions->addArguments(['-headless']);

        $firefoxProfileEncoded = (new FirefoxProfile())->encode();

        return [
            'changed name' => [
                new DesiredCapabilities([
                    WebDriverCapabilityType::BROWSER_NAME => WebDriverBrowserType::CHROME,
                    WebDriverCapabilityType::VERSION => '67.0.1',
                    WebDriverCapabilityType::PLATFORM => WebDriverPlatform::LINUX,
                    WebDriverCapabilityType::ACCEPT_SSL_CERTS => true,
                ]),
                [
                    'browserName' => 'chrome',
                    'browserVersion' => '67.0.1',
                    'platformName' => 'linux',
                    'acceptInsecureCerts' => true,
                ],
            ],
            'removed capabilities' => [
                new DesiredCapabilities([
                    WebDriverCapabilityType::WEB_STORAGE_ENABLED => true,
                    WebDriverCapabilityType::TAKES_SCREENSHOT => false,
                ]),
                [],
            ],
            'custom invalid capability should be removed' => [
                new DesiredCapabilities([
                    'customInvalidCapability' => 'shouldBeRemoved',
                ]),
                [],
            ],
            'already W3C capabilities' => [
                new DesiredCapabilities([
                    'pageLoadStrategy' => 'eager',
                    'strictFileInteractability' => false,
                ]),
                [
                    'pageLoadStrategy' => 'eager',
                    'strictFileInteractability' => false,
                ],
            ],
            '"ANY" platform should be completely removed' => [
                new DesiredCapabilities([
                    WebDriverCapabilityType::PLATFORM => WebDriverPlatform::ANY,
                ]),
                [],
            ],
            'custom vendor extension' => [
                new DesiredCapabilities([
                    'vendor:prefix' => 'vendor extension should be kept',
                ]),
                [
                    'vendor:prefix' => 'vendor extension should be kept',
                ],
            ],
            'chromeOptions should be an object if empty' => [
                new DesiredCapabilities([
                    ChromeOptions::CAPABILITY => new ChromeOptions(),
                ]),
                [
                    'goog:chromeOptions' => new \ArrayObject(),
                ],
            ],
            'chromeOptions should be converted' => [
                new DesiredCapabilities([
                    ChromeOptions::CAPABILITY => $chromeOptions,
                ]),
                [
                    'goog:chromeOptions' => new \ArrayObject(
                        [
                            'args' => ['--headless'],
                        ]
                    ),
                ],
            ],
            'chromeOptions as W3C capability should be converted' => [
                new DesiredCapabilities([
                    ChromeOptions::CAPABILITY_W3C => $chromeOptions,
                ]),
                [
                    'goog:chromeOptions' => new \ArrayObject(
                        [
                            'args' => ['--headless'],
                        ]
                    ),
                ],
            ],
            'firefox_profile should be converted' => [
                new DesiredCapabilities([
                    FirefoxDriver::PROFILE => $firefoxProfileEncoded,
                ]),
                [
                    'moz:firefoxOptions' => [
                        'profile' => $firefoxProfileEncoded,
                    ],
                ],
            ],
            'firefox_profile should not be overwritten if already present' => [
                new DesiredCapabilities([
                    FirefoxDriver::PROFILE => $firefoxProfileEncoded,
                    FirefoxOptions::CAPABILITY => ['profile' => 'w3cProfile'],
                ]),
                [
                    'moz:firefoxOptions' => [
                        'profile' => 'w3cProfile',
                    ],
                ],
            ],
            'firefox_profile should be merged with moz:firefoxOptions if they already exists' => [
                new DesiredCapabilities([
                    FirefoxDriver::PROFILE => $firefoxProfileEncoded,
                    FirefoxOptions::CAPABILITY => $firefoxOptions,
                ]),
                [
                    'moz:firefoxOptions' => [
                        'profile' => $firefoxProfileEncoded,
                        'args' => ['-headless'],
                        'prefs' => FirefoxOptionsTest::EXPECTED_DEFAULT_PREFS,
                    ],
                ],
            ],
        ];
    }
}
