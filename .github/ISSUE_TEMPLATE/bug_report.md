---
name: 🐛 Bug report
about: Create a bug report to help us improve php-webdriver
title: ''
labels: bug
assignees: ''
---

<!--
For questions, ask in Discussions: https://github.com/php-webdriver/php-webdriver/discussions

If reporting a bug, please FILL THE TEMPLATE COMPLETELY, otherwise the community and maintainers
cannot provide a prompt feedback and help solving the issue.
-->

### Bug description
<!-- A clear description of what the bug is. -->

### How could the issue be reproduced

Steps to reproduce the behavior:
1. 
2. 
3. ...

<!-- Please fill everything relevant - the exact code you use, how you initialize the WebDriver, HTML snippet or URL of the page where you encounter the issue etc. -->

```php
// You can insert your PHP code here (or remove this block if it is not relevant for the issue).

// For example you can provide how you create WebDrivere instance:
$capabilities = DesiredCapabilities::chrome();
$driver = RemoteWebDriver::create('http://localhost:4444/', $capabilities);

// And the code you use to execute the php-webdriver commands, for example:
$driver->get('http://site.localhost/foo.html');
$button = $driver->findElement(WebDriverBy::cssSelector('#foo'));
$button->click();
```

```html
<!-- You can also provide HTML snippet of the relevant part of page -->
<div>
  <button id="foo">Foo</button>
</div>
```

### Expected behavior
<!-- A clear and concise description of what you expected to happen. -->

### Details
<!-- Please fill relevant following items: -->

* Php-webdriver version: <!-- You can run `composer show php-webdriver/webdriver` to find the version -->
* PHP version:  <!-- You can run `php -v` to find the version -->
* How do you start the browser driver or Selenium server:
  <!-- For example Selenium server, chromedriver command, Laravel Dusk, Docker, SauceLabs etc. -->
  <!-- If relevant, provide the complete command you use to start the browser driver or Selenium server -->
* Selenium server version: <!-- Relevant only if you use Selenium server -->
* Browser driver (chromedriver/geckodriver...) version: <!-- Run `chromedriver --version` or `geckodriver --version` -->
* Browser used + version:
* Operating system:

### Additional context
<!-- Add any other context about the problem here. -->
