# Contributing to php-webdriver

We love to have your help to make php-webdriver better!
 
Feel free to open an [issue](https://github.com/php-webdriver/php-webdriver/issues) if you run into any problem, or
send a pull request (see bellow) with your contribution.

## Before you contribute

Do not hesitate to ask for a guidance before you implement notable change, or a new feature - use the associated [issue](https://github.com/php-webdriver/php-webdriver/issues) or use [Discussions](https://github.com/php-webdriver/php-webdriver/discussions).
Because any new code means increased effort in library maintenance (which is being done by volunteers in their free time),
please understand not every pull request is automatically accepted. This is why we recommend using the mentioned channels to discuss bigger changes in the source code first.

When you are going to contribute, also please keep in mind that this webdriver client aims to be similar to clients in languages Java/Ruby/Python/C#.
Here is the [official documentation](https://www.selenium.dev/documentation/en/) and overview of [the official Java API](http://seleniumhq.github.io/selenium/docs/api/java/)

## Workflow when contributing a patch

1. Fork the project on GitHub
2. Implement your code changes into separate branch
3. Make sure all PHPUnit tests passes and code-style matches PSR-2 (see below). We also have CI builds which will automatically run tests on your pull request. Make sure to fix any reported issues reported by these automated tests.
4. When implementing a notable change, fix or a new feature, add record to the Unreleased section of [CHANGELOG.md](../CHANGELOG.md)
5. Submit your [pull request](https://github.com/php-webdriver/php-webdriver/pulls) against `main` branch

### Run automated code checks

To make sure your code comply with [PSR-2](http://www.php-fig.org/psr/psr-2/) coding style, tests passes and to execute other automated checks, run locally:

```sh
composer all
```

To run functional tests locally there is some additional setup needed - see below. Without this setup, functional tests will be skipped.


For easier development there are also few other prepared commands:
- `composer fix` - to auto-fix the codestyle and composer.json
- `composer analyze` - to run only code analysis (without tests)
- `composer test` - to run all tests

### Unit tests

There are two test-suites: one with **unit tests** only (`unit`), and second with **functional tests** (`functional`),
which requires running Selenium server and local PHP server.

To execute **all tests** in both suites run:

```sh
composer test
```

If you want to execute **just the unit tests**, run:

```sh
composer test -- --testsuite unit
```

**Functional tests** are run against a real browser. It means they take a bit longer and also require an additional setup:
you must first [download](https://www.selenium.dev/downloads/) and start the Selenium standalone server,
then start the local PHP server which will serve the test pages and then run the `functional` test suite:

```sh
export BROWSER_NAME="htmlunit" # see below for other browsers
java -jar selenium-server-standalone-X.X.X.jar -log selenium.log &
php -S localhost:8000 -t tests/functional/web/ &
# Use following to run both unit and functional tests
composer all
# Or this to run only functional tests:
composer test -- --testsuite functional
```

If you want to run tests in different browser then "htmlunit" (Chrome or Firefox), you need to setup the browser driver (Chromedriver/Geckodriver), as it is [explained in wiki](https://github.com/php-webdriver/php-webdriver/wiki/Chrome)
and then the `BROWSER_NAME` environment variable:

```sh
...
export BROWSER_NAME="chrome"
composer all
```

To test with Firefox/Geckodriver, you must also set `GECKODRIVER` environment variable:

```sh
export GECKODRIVER=1
export BROWSER_NAME="firefox"
composer all
```
