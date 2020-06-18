# php-webdriver – Selenium WebDriver bindings for PHP

[![Latest Stable Version](https://img.shields.io/packagist/v/php-webdriver/webdriver.svg?style=flat-square)](https://packagist.org/packages/php-webdriver/webdriver)
[![Travis Build](https://img.shields.io/travis/php-webdriver/php-webdriver/main.svg?style=flat-square)](https://travis-ci.com/php-webdriver/php-webdriver)
[![Sauce Test Status](https://saucelabs.com/buildstatus/php-webdriver)](https://saucelabs.com/u/php-webdriver)
[![Total Downloads](https://img.shields.io/packagist/dd/php-webdriver/webdriver.svg?style=flat-square)](https://packagist.org/packages/php-webdriver/webdriver)

## Description
Php-webdriver library is PHP language binding for Selenium WebDriver, which allows you to control web browsers from PHP.

This library is compatible with Selenium server version 2.x, 3.x and 4.x.

The library supports [JsonWireProtocol](https://github.com/SeleniumHQ/selenium/wiki/JsonWireProtocol) and also
implements **experimental support** of [W3C WebDriver](https://w3c.github.io/webdriver/webdriver-spec.html).
The W3C WebDriver support is not yet full-featured, however it should allow to control Firefox via Geckodriver and new
versions of Chrome and Chromedriver with just a slight limitations.

The concepts of this library are very similar to the "official" Java, .NET, Python and Ruby bindings from the
[Selenium project](https://github.com/SeleniumHQ/selenium/).

Looking for API documentation of php-webdriver? See [https://php-webdriver.github.io/php-webdriver/](https://php-webdriver.github.io/php-webdriver/latest/)

Any complaints, questions, or ideas? Post them in the user group https://www.facebook.com/groups/phpwebdriver/.

## Installation

Installation is possible using [Composer](https://getcomposer.org/).

If you don't already use Composer, you can download the `composer.phar` binary:

    curl -sS https://getcomposer.org/installer | php

Then install the library:

    php composer.phar require php-webdriver/webdriver

## Upgrade from version <1.8.0

Starting from version 1.8.0, the project has been renamed from `facebook/php-webdriver` to `php-webdriver/webdriver`.

In order to receive the new version and future updates, **you need to rename it in your composer.json**:

```diff
"require": {
-    "facebook/webdriver": "(version you use)",
+    "php-webdriver/webdriver": "(version you use)",
}
```

and run `composer update`.

## Getting started

### 1. Start server (aka. remote end)

To control a browser, you need to start a *remote end* (server), which will listen to the commands sent
from this library and will execute them in the respective browser.

This could be Selenium standalone server, but for local development, you can send them directly to so-called "browser driver" like Chromedriver or Geckodriver.

#### a) Chromedriver

Install the latest Chrome and [Chromedriver](https://sites.google.com/a/chromium.org/chromedriver/downloads).
Make sure to have a compatible version of Chromedriver and Chrome!

Run `chromedriver` binary, you can pass `port` argument, so that it listens on port 4444:

```sh
chromedriver --port=4444
```

#### b) Geckodriver

Install the latest Firefox and [Geckodriver](https://github.com/mozilla/geckodriver/releases).
Make sure to have a compatible version of Geckodriver and Firefox!

Run `geckodriver` binary (it start to listen on port 4444 by default):

```sh
geckodriver
```

#### c) Selenium standalone server

[Selenium server](https://selenium.dev/downloads/) is useful especially when you need to execute multiple tests at once
or your tests are run in different browsers - like on your CI server.

Selenium server receives commands and starts new sessions using browser drivers acting like hub distributing the commands
among multiple nodes.

To run the standalone server, download [`selenium-server-standalone-#.jar` file](http://selenium-release.storage.googleapis.com/index.html)
(replace # with the current server version). Keep in mind **you must have Java 8+ installed**.

Run the server:

```sh
java -jar selenium-server-standalone-#.jar
```

You may need to provide path to `chromedriver`/`geckodriver` binary (if they are not placed in system `PATH` directory):

```sh
# Chromedriver:
java -Dwebdriver.chrome.driver="/opt/chromium-browser/chromedriver" -jar vendor/bin/selenium-server-standalone-#.jar
# Geckodriver:
java -Dwebdriver.gecko.driver="/home/john/bin/geckodriver" -jar vendor/bin/selenium-server-standalone-#.jar

# (These options could be combined)
```

If you want to distribute browser sessions among multiple servers ("grid mode" - one Selenium hub and multiple Selenium nodes) please
[refer to the documentation](https://selenium.dev/documentation/en/grid/).

#### d) Docker

Selenium server could also be started inside Docker container - see [docker-selenium project](https://github.com/SeleniumHQ/docker-selenium).

### 2. Create a Browser Session

When creating a browser session, be sure to pass the url of your running server.

For example:

```php
// Chromedriver (if started using --port=4444 as above)
$host = 'http://localhost:4444';
// Geckodriver
$host = 'http://localhost:4444';
// selenium-server-standalone-#.jar (version 2.x or 3.x)
$host = 'http://localhost:4444/wd/hub';
// selenium-server-standalone-#.jar (version 4.x)
$host = 'http://localhost:4444';
```

Now you can start browser of your choice:

```php
use Facebook\WebDriver\Remote\RemoteWebDriver;

// Chrome
$driver = RemoteWebDriver::create($host, DesiredCapabilities::chrome());
// Firefox
$driver = RemoteWebDriver::create($host, DesiredCapabilities::firefox());
// Microsoft Edge
$driver = RemoteWebDriver::create($host, DesiredCapabilities::microsoftEdge());
```

### 3. Customize Desired Capabilities

Desired capabilities define properties of the browser you are about to start.

They can be customized:

```php
use Facebook\WebDriver\Remote\DesiredCapabilities;

$desiredCapabilities = DesiredCapabilities::firefox();

// Disable accepting SSL certificates
$desiredCapabilities->setCapability('acceptSslCerts', false);

// Run headless firefox
$desiredCapabilities->setCapability('moz:firefoxOptions', ['args' => ['-headless']]);

$driver = RemoteWebDriver::create($host, $desiredCapabilities);
```

They can also be used to [configure proxy server](https://github.com/php-webdriver/php-webdriver/wiki/HowTo-Work-with-proxy) which the browser should use.
To configure Chrome, you may use ChromeOptions - see [details in our wiki](https://github.com/php-webdriver/php-webdriver/wiki/ChromeOptions).

* See [legacy JsonWire protocol](https://github.com/SeleniumHQ/selenium/wiki/DesiredCapabilities) documentation or [W3C WebDriver specification](https://w3c.github.io/webdriver/#capabilities) for more details.

**NOTE:** Above snippets are not intended to be a working example by simply copy-pasting. See [example.php](example.php) for a working example.

## Changelog
For latest changes see [CHANGELOG.md](CHANGELOG.md) file.

## More information

Some basic usage example is provided in [example.php](example.php) file.

How-tos are provided right here in [our GitHub wiki](https://github.com/php-webdriver/php-webdriver/wiki).

You may also want to check out the Selenium [docs](https://selenium.dev/documentation/en/) and [wiki](https://github.com/SeleniumHQ/selenium/wiki).

## Testing framework integration

To take advantage of automatized testing you may want to integrate php-webdriver to your testing framework.
There are some projects already providing this:

- [Symfony Panther](https://github.com/symfony/panther) uses php-webdriver and integrates with PHPUnit using `PantherTestCase`
- [Laravel Dusk](https://laravel.com/docs/dusk) is another project using php-webdriver, could be used for testing via `DuskTestCase`
- [Steward](https://github.com/lmc-eu/steward) integrates php-webdriver directly to [PHPUnit](https://phpunit.de/), and provides parallelization
- [Codeception](http://codeception.com) testing framework provides BDD-layer on top of php-webdriver in its [WebDriver module](http://codeception.com/docs/modules/WebDriver)
- You can also check out this [blogpost](http://codeception.com/11-12-2013/working-with-phpunit-and-selenium-webdriver.html) + [demo project](https://github.com/DavertMik/php-webdriver-demo), describing simple [PHPUnit](https://phpunit.de/) integration

## Support

We have a great community willing to help you!

- **Via our Facebook Group** - If you have questions or are an active contributor consider joining our [facebook group](https://www.facebook.com/groups/phpwebdriver/) and contribute to communal discussion and support
- **Via StackOverflow** - You can also [ask a question](https://stackoverflow.com/questions/ask?tags=php+selenium-webdriver) or find many already answered question on StackOverflow
- **Via GitHub** - Another option if you have a question (or bug report) is to [submit it here](https://github.com/php-webdriver/php-webdriver/issues/new) as a new issue

## Contributing

We love to have your help to make php-webdriver better. See [CONTRIBUTING.md](.github/CONTRIBUTING.md) for more information about contributing and developing php-webdriver.
