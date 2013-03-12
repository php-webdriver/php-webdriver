php-webdriver -- A very thin wrapper of WebDriver
=================================================

##  DESCRIPTION

This client aims to be as thin as possible, abusing the dynamic nature of PHP to allow almost all API calls to be a direct transformation of what is defined in the WebDriver protocol itself.

Most clients require you to first read the protocol to see what's possible, then study the client itself to see how to call it.  This hopes to eliminate the latter step, and invites you to rely almost exclusively on the [Selenium JSON Wire Protocol](http://code.google.com/p/selenium/wiki/JsonWireProtocol).

Each command is just the name of a function call, and each additional path is just another chained function call.  The function parameter is then either an `array()` if the command takes JSON parameters, or an individual primitive if it takes a URL parameter.

The function's return value is exactly what is returned from the server as part of the protocol definition.  If an error is returned, the function will throw the appropriate `WebDriverException` instance.

Note - This is a maintained clone of [facebook/php-webdriver](https://github.com/facebook/php-webdriver) with following differences:

*   Class names being slightly different for packaging within PEAR
*   Implementation of WebDriverWait

## RELEASE NOTES

What got added, what got removed and what got fixed is listed in the [Release Notes](https://github.com/Element-34/php-webdriver/wiki/Release-Notes). Well, to varying degrees of detail at any rate.

##  GETTING STARTED

*   This driver has been packaged for distribution via PEAR. So...

```php
pear channel-discover element-34.github.com/pear
pear install -f element-34/PHPWebDriver
```

Note: if you receive a 404 during the channel-discover set, check that you are using the current version of PEAR. If not you need to

```php
pear upgrade pear
```

*   All you need as the server for this client is the [selenium-server-standalone-#.jar](http://code.google.com/p/selenium/downloads/list). 

*   Download and run that file, replacing # with the current server version.

        java -jar selenium-server-standalone-#.jar

*   Then when you create a session, be sure to pass the url to where your server is running.

```php
// This would be the url of the host running the server-standalone.jar
$wd_host = 'http://localhost:4444/wd/hub';
$web_driver = new PHPWebDriver_WebDriver($wd_host);

// First param to session() is the 'browserName' (default = 'firefox')
// Second param is a JSON object of additional 'desiredCapabilities'

// POST /session
$session = $web_driver->session('firefox');
```

*   Valid browser strings
    * firefox
    * chrome
    * ie
    * internet explorer
    * opera
    * htmlunit
    * htmlunitjs
    * iphone
    * ipad
    * android

##  SIMPLE EXAMPLES

### Note that all of these match the [protocol](http://code.google.com/p/selenium/wiki/JsonWireProtocol) exactly
*   Move to a specific spot on the screen

        // POST /session/:sessionId/moveto
        $session->moveto(array('xoffset' => 3, 'yoffset' => 300));

*   Get the current url

        // GET /session/:sessionId/url
        $session->url();

*   Get a list of window handles for all open windows

        // GET /session/:sessionId/window_handles
        $session->window_handles();

*   Click an element

        // POST session/:sessionId/element/:id/click
        $session->element($using, $value)->click("")
        
*   Double-click an element on a touch screen

        // POST session/:sessionId/touch/doubleclick
        $session->touch()->doubleclick(array('element' => $element->getID())

*   Check if two elements are equal

        // GET /session/:sessionId/element/:id/equals/:other
        $element->equals($other_element->getID()))

*   Get value of a css property on element

        // GET /session/:sessionId/element/:id/css/:propertyName
        $element->css($property_name)

## 'GET', 'POST', or 'DELETE' to the same command examples

### When you can do multiple http methods for the same command, call the command directly for the 'GET', and prepend the http method for the 'POST' or 'DELETE'.

*   Set landscape orientation with 'POST'

        // POST /session/:sessionId/orientation
        $session->postOrientation(array('orientation' => 'LANDSCAPE'));

*   Get landscape orientation with normal 'GET'

        // GET /session/:sessionId/orientation
        $session->orientation();

*   Set size of window that has $window_handle with 'POST'

        // If excluded, $window_handle defaults to 'current'
        // POST /session/:sessionId/window/:windowHandle/size
        $session
          ->window($window_handle)
          ->postSize(array('width' => 10, 'height' => 10));

*   Get current window size with 'GET'

        // GET /session/:sessionId/window/:windowHandle/size
        $session->window()->size();

## Some unavoidable exceptions to direct [protocol](http://code.google.com/p/selenium/wiki/JsonWireProtocol) translation.

*   Opening pages

        // POST /session/:sessionId/url
        $session->open('http://www.facebook.com');

*   Dealing with the session

        // DELETE /session/:sessionId
        $session->close();

        // GET /session/:sessionId
        $session->capabilities();
        
*   To find elements

```php
// POST /session/:sessionId/element
$element = $session->element($using, $value);
```

```php
// POST /session/:sessionId/elements
$session->elements($using, $value);
```

```php
// POST /session/:sessionId/element/:id/element
$element->element($using, $value);
```

```php
// POST /session/:sessionId/element/:id/elements
$element->elements($using, $value);
```

`$using` is the location method either as a string value like the following:
* id
* xpath
* link text
* partial link text
* name
* tag name
* class name
* css selector

or by a const defined in WebDriverBy.php (see below). The advantage to this is that you will know much faster (as in compile time) whether you have fat-fingered something.
* `ID`
* `XPATH`
* `LINK_TEXT`
* `PARTIAL_LINK_TEXT`
* `NAME`
* `TAG_NAME`
* `CLASS_NAME`
* `CSS_SELECTOR`

In other words, the following are equivilant:

```php
// POST /session/:sessionId/element
$element = $session->element("id", $value);
```

```php
// POST /session/:sessionId/element
$element = $session->element(PHPWebDriver_WebDriverBy::ID, $value);
```

*   To get the active element

        // POST /session/:sessionId/element/active
        $session->activeElement();

*   To manipulate cookies

        // GET /session/:sessionId/cookie
        $session->getAllCookies();
        
        // GET /session/:sessionId/cookie
        $session->getCookie($name);

        // POST /session/:sessionId/cookie
        //
        // $cookie_array mandatory fields
        // - name: string
        // - vale: string
        //
        // $cookie_array optional fields
        // - path: string
        // - domain: string
        // - secure: boolean
        // - expiry: number (seconds since epoch)
        $session->setCookie($cookie_array);

        // DELETE /session/:sessionId/cookie
        $session->deleteAllCookies()

        // DELETE /session/:sessionId/cookie/:name
        $session->deleteCookie($name)

*   To manipulate windows

        // POST /session/:sessionId/window
        $session->focusWindow($window_handle);

        // DELETE /session/:sessionId/window
        $session->deleteWindow();
        
## Waiting

### The until function will fire until it returns something PHP considers True

*   To wait for an element that you want to use then

        $w = new PHPWebDriver_WebDriverWait($session);
        $e = $w->until(
                function($session) {
                  return $session->element(PHPWebDriver_WebDriverBy::ID, "overlayPanelProfileovolp-pad");
                }
             );

*   To wait for an element's presence

         $w = new PHPWebDriver_WebDriverWait($session);
         $w->until(
            function($session) {
              return count($session->elements(PHPWebDriver_WebDriverBy::ID, "overlayPanelProfileovolp-pad"));
            }
         );

## Timeouts

*   To configure implicit waits (in seconds)

        $this->$session->implicitlyWait(3);

*   To disable implicit waits

        $this->$session->implicitlyWait(0);

*   How long to wait for an execute or execute_async to (in seconds)

        $this->$session->setScriptTimeout(3);

*   How long to wait page loads to complete (in seconds)

        $this->$session->setPageLoadTimeout(3);
        
*   How to set the above timeouts directly. Don't do this unless you really need ms granularity

        $this->$session->setTimeouts(array('type' => 'implicit', 'ms' => 5));
        $this->$session->setTimeouts(array('type' => 'script', 'ms' => 5));
        $this->$session->setTimeouts(array('type' => 'page load', 'ms' => 5));
        
## Interacting with elements

*   Sending characters per the protocol (yuck)

        $e1 = $this->session->element(PHPWebDriver_WebDriverBy::ID, "some id");
        $e1->value(array("value" => array("pumpkins")));
        
*   Sending characters a little nicer

        $e2 = $this->session->element(PHPWebDriver_WebDriverBy::ID, "some id");
        $e2->sendKeys("turtles");
        
*   Sending a 'special' character (see list at [WebDriverKeys.php](https://raw.github.com/Element-34/php-webdriver/master/PHPWebDriver/WebDriverKeys.php/))

        $e3 = $this->session->element(PHPWebDriver_WebDriverBy::ID, "some id");
        $e3->sendKeys(PHPWebDriver_WebDriverKeys::SpaceKey());
        
*   Advancing the page by using the space key

        $e4 = $this->session->element(PHPWebDriver_WebDriverBy::TAG_NAME, "body");
        $e4->sendKeys(PHPWebDriver_WebDriverKeys::SpaceKey());

## Proxy

*   Http proxying

        $server = 'http://localhost:4444/wd/hub';
        $driver = new PHPWebDriver_WebDriver($server);
        $desired_capabilities = array();
        $proxy = new PHPWebDriver_WebDriverProxy();
        $proxy->httpProxy = '127.0.0.1:9091;
        $proxy->add_to_capabilities($desired_capabilities);
        $session = $driver->session('firefox', $desired_capabilities);

## Screenshotting

*   Screenshots are returned as base64 strings from the server

        $img = $session->screenshot();
        $data = base64_decode($img);
        $file = 'gramophon.com.png';
        $success = file_put_contents($file, $data);
        
## Frames

*   Change focus to another frame

        // find your iframe
        $iframe = self::$session->element(PHPWebDriver_WebDriverBy::CSS_SELECTOR, "iframe");
        // switch context to it
        self::$session->switch_to_frame($iframe);
        // interact
        $ps = self::$session->elements(PHPWebDriver_WebDriverBy::CSS_SELECTOR, "p");
        $this->assertEquals(count($ps), 6);
        // switch back
        self::$session->switch_to_frame();
        
## Alerts


*   switch to an alert

        $p = $this->session->switch_to_alert();

*   get the text of an alert/prompt

        $p->text;

*   accept an alert/prompt

        $p->accept();

*   dismiss an alert/prompt

        $p->dismiss();

*   set some text on a prompt (doing this on an alert will throw `PHPWebDriver_ElementNotDisplayedWebDriverError`)

        $p->sendKeys('cheese');
        $p->accept();

        
