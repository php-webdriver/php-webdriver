<?php

namespace Facebook\WebDriver\Firefox;

use PHPUnit\Framework\TestCase;

class FirefoxOptionsTest extends TestCase
{
    /** @var array<string,bool> */
    const EXPECTED_DEFAULT_PREFS = [
        FirefoxPreferences::READER_PARSE_ON_LOAD_ENABLED => false,
        FirefoxPreferences::DEVTOOLS_JSONVIEW => false,
    ];

    public function testShouldBeConstructedWithDefaultOptions()
    {
        $options = new FirefoxOptions();

        $this->assertSame(
            [
                'prefs' => self::EXPECTED_DEFAULT_PREFS,
            ],
            $options->toArray()
        );
    }

    public function testShouldAddCustomOptions()
    {
        $options = new FirefoxOptions();

        $options->setOption('binary', '/usr/local/firefox/bin/firefox');

        $this->assertSame(
            [
                'binary' => '/usr/local/firefox/bin/firefox',
                'prefs' => self::EXPECTED_DEFAULT_PREFS,
            ],
            $options->toArray()
        );
    }

    public function testShouldOverwriteDefaultOptionsWhenSpecified()
    {
        $options = new FirefoxOptions();

        $options->setPreference(FirefoxPreferences::READER_PARSE_ON_LOAD_ENABLED, true);

        $this->assertSame(
            [
                'prefs' => [
                    FirefoxPreferences::READER_PARSE_ON_LOAD_ENABLED => true,
                    FirefoxPreferences::DEVTOOLS_JSONVIEW => false,
                ],
            ],
            $options->toArray()
        );
    }

    public function testShouldSetCustomPreference()
    {
        $options = new FirefoxOptions();

        $options->setPreference('browser.startup.homepage', 'https://github.com/php-webdriver/php-webdriver/');

        $this->assertSame(
            [
                'prefs' => [
                    FirefoxPreferences::READER_PARSE_ON_LOAD_ENABLED => false,
                    FirefoxPreferences::DEVTOOLS_JSONVIEW => false,
                    'browser.startup.homepage' => 'https://github.com/php-webdriver/php-webdriver/',
                ],
            ],
            $options->toArray()
        );
    }

    public function testShouldAddArguments()
    {
        $options = new FirefoxOptions();

        $options->addArguments(['-headless', '-profile', '/path/to/profile']);

        $this->assertSame(
            [
                'args' => ['-headless', '-profile', '/path/to/profile'],
                'prefs' => self::EXPECTED_DEFAULT_PREFS,
            ],
            $options->toArray()
        );
    }

    public function testShouldJsonSerializeToArrayObject()
    {
        $options = new FirefoxOptions();
        $options->setOption('binary', '/usr/local/firefox/bin/firefox');

        $jsonSerialized = $options->jsonSerialize();

        $this->assertInstanceOf(\ArrayObject::class, $jsonSerialized);
        $this->assertSame('/usr/local/firefox/bin/firefox', $jsonSerialized['binary']);
    }

    public function testShouldNotAllowToSetArgumentsOptionDirectly()
    {
        $options = new FirefoxOptions();

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Use addArguments() method to add Firefox arguments');
        $options->setOption('args', []);
    }

    public function testShouldNotAllowToSetPreferencesOptionDirectly()
    {
        $options = new FirefoxOptions();

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Use setPreference() method to set Firefox preferences');
        $options->setOption('prefs', []);
    }
}
