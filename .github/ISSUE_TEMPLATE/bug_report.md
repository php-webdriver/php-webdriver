---
name: 🐛 Bug report
about: Create a bug report to help us improve php-webdriver
title: ''
labels: bug
assignees: ''
---

<!--
For questions, ask in Discussions: https://github.com/php-webdriver/php-webdriver/discussions
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
$driver = RemoteWebDriver::create('http://localhost:4444/', $capabilities);

// And the code you use to execute the php-webdriver commands, for example:
$driver->get('http://site.localhost/foo.html');
$button = $driver->findElement(WebDriverBy::cssSelector('#foo');
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

* Php-webdriver version: 
* PHP version: 
* How do you start the browser: (for example: via Selenium server / with chromedriver command / using Laravel Dusk / in Docker etc.)
* Selenium server version: 
* Operating system: 
* Browser used + version: 
* Browser driver (chromedriver/geckodriver...) version: 

### Additional context
<!-- Add any other context about the problem here. -->
