php-webdriver – WebDriver bindings for PHP
===========================================

## Description
Php-webdriver library is PHP language binding for Selenium WebDriver, which allows you to control web browsers from PHP.

This WebDriver client aims to be as close as possible to bindings in other languages.
The concepts are very similar to the Java, .NET, Python and Ruby bindings for WebDriver.

This is new version of PHP client, rewritten from scratch starting 2013.
Using the old version? Check out Adam Goucher's fork of it at https://github.com/Element-34/php-webdriver

Looking for API documentation of php-webdriver? See http://facebook.github.io/php-webdriver/

Any complaint, question, idea? You can post it on the user group https://www.facebook.com/groups/phpwebdriver/.

## Installation

Installation is possible using [Composer](https://getcomposer.org/).

If you don't already use Composer, you can download the `composer.phar` binary:

    curl -sS https://getcomposer.org/installer | php

Then install the library:

    php composer.phar require facebook/webdriver

## Getting started

All you need as the server for this client is the `selenium-server-standalone-#.jar` file provided here: http://selenium-release.storage.googleapis.com/index.html

Download and run that file, replacing # with the current server version.

    java -jar selenium-server-standalone-#.jar

Then when you create a session, be sure to pass the url to where your server is running.

```php
// This would be the url of the host running the server-standalone.jar
$host = 'http://localhost:4444/wd/hub'; // this is the default
```

* Launch Firefox:

    ```php
    $driver = RemoteWebDriver::create($host, DesiredCapabilities::firefox());
    ```

* Launch Chrome:

    ```php
    $driver = RemoteWebDriver::create($host, DesiredCapabilities::chrome());
    ```

You can also customize the desired capabilities:

```php
$desired_capabilities = DesiredCapabilities::firefox();
$desired_capabilities->setCapability('acceptSslCerts', false);
$driver = RemoteWebDriver::create($host, $desired_capabilities);
```

* See https://github.com/SeleniumHQ/selenium/wiki/DesiredCapabilities for more details.

## Changelog
For latest changes see [CHANGELOG.md](CHANGELOG.md) file.

## More information

Check out the Selenium docs and wiki at http://docs.seleniumhq.org/docs/ and https://code.google.com/p/selenium/wiki

Learn how to integrate it with PHPUnit [Blogpost](http://codeception.com/11-12-2013/working-with-phpunit-and-selenium-webdriver.html) | [Demo Project](https://github.com/DavertMik/php-webdriver-demo)

## Support

We have a great community willing to try and help you!

Currently we offer support in two manners:

### Via our Facebook Group

If you have questions or are an active contributor consider joining our facebook group and contributing to the communal discussion and support

https://www.facebook.com/groups/phpwebdriver/

### Via Github

If you're reading this you've already found our Github repository. If you have a question, feel free to submit it as an issue and our staff will do their best to help you as soon as possible.

## Contributing

We love to have your help to make php-webdriver better. See [CONTRIBUTING.md](CONTRIBUTING.md) for more information
about contributing and developing php-webdriver.
