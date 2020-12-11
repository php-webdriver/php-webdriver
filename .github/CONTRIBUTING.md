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
 
### Run unit tests

There are two test-suites: one with unit tests only, second with functional tests, which require running selenium server.

To execute all tests simply run:

    ./vendor/bin/phpunit

If you want to execute just the unit tests, run:

    ./vendor/bin/phpunit --testsuite unit

For the functional tests you must first [download](http://selenium-release.storage.googleapis.com/index.html) and start
the selenium standalone server, start the local PHP server which will serve the test pages and then run the `functional`
test suite:

    java -jar selenium-server-standalone-3.9.1.jar -log selenium.log &
    php -S localhost:8000 -t tests/functional/web/ &
    ./vendor/bin/phpunit --testsuite functional

The functional tests will be started in HtmlUnit headless browser by default. If you want to run them in eg. Firefox,
simply set the `BROWSER_NAME` environment variable:

    ...
    export BROWSER_NAME="firefox"
    ./vendor/bin/phpunit --testsuite functional

To test with Geckodriver, [download](https://github.com/mozilla/geckodriver/releases) and start the server, then run:

    export GECKODRIVER=1
    export BROWSER_NAME=firefox
    ./vendor/bin/phpunit --testsuite functional

### Check coding style

Your code-style should comply with [PSR-2](http://www.php-fig.org/psr/psr-2/). To make sure your code matches this requirement run:

    composer codestyle:check

To auto-fix the codestyle simply run:

    composer codestyle:fix
