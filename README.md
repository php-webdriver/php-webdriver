php-webdriver -- A very thin wrapper of WebDriver
=================================================

## DESCRIPTION

This client aims to be as this as possible, abusing the dynamic nature of PHP to allow almost all API calls to be a direct transformation of what is defined in the WebDriver protocol itself.

Most clients require you to first read the protocol to see what's possible, then study the client itself to see how to call it.  This hopes to eliminate the latter step, and invites you to rely almost exclusively on http://code.google.com/p/selenium/wiki/JsonWireProtocol

Each command is just the name of a function call, and each additional path is just another chained function call.  The function parameter is then either an array() if the command takes JSON parameters, or an individual primitive if it takes a URL parameter.

## SIMPLE EXAMPLES
### Note that all of these match the Protocol exactly

* Get a session (opens a new browser window)

<code>
  $web_driver = new WebDriver(); // could pass a host besides localhost

  $session = $web_driver->session(); // could pass a browser name
</code>

* Move to a specific spot on the screen

  $session->moveto(array('xoffset' => 3, 'yoffset' => 300));

* Change asynchronous script timeout

  $session->timeouts()->async_script(array('ms' => 2000));

* Touch screen

  $session->touch()->scroll($element->getID())

* Check if two elements are equal

  $element->equals($other_element)

* Get value of css property on element

  $element->css($property_name)

## 'GET', 'POST', or 'DELETE' to the same command examples

### If you can do multiple http methods for the same command, such as 'orientation', where 'POST' changes the orientation but 'GET' fetches it, call the command directly for the getter, and prepend the http method for the writers.

* Set landscape orientation

  $session->postOrientation(array('orientation' => 'LANDSCAPE'));

* Get landscape orientation

  $session->orientation();

## A few Element/Cookie/Session convenience exceptions.

* Use element($using, $value) and elements($using, $value)
* Use getAllCookies(), setCookie(), deleteAllCookies(), and deleteCookie($name)
* Visit pages with open(), close the browser window with close()
