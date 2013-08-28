php-webdriver -- WebDriver bindings for PHP
===========================================

##  DESCRIPTION

This WebDriver client aims to be as close as possible to bindings in other languages. The concepts are very similar to the Java, .NET, Python and Ruby bindings for WebDriver.

Looking for documentation about Selenium WebDriver? See http://docs.seleniumhq.org/docs/ and https://code.google.com/p/selenium/wiki

The PHP client was rewritten from scratch. Using the old version? Check out Adam Goucher's fork of it at https://github.com/Element-34/php-webdriver

Any complaint, question, idea? You can post it on the user group https://www.facebook.com/groups/459991440774500/.

##  GETTING THE CODE

*   git clone git@github.com:facebook/php-webdriver.git

*   If you are using Packagist, add the dependency. https://packagist.org/packages/facebook/webdriver

        {
          "require": {
            "facebook/webdriver": "dev-master"
          }
        }
   

##  GETTING STARTED

*   All you need as the server for this client is the selenium-server-standalone-#.jar file provided here:  http://code.google.com/p/selenium/downloads/list

*   Download and run that file, replacing # with the current server version.

        java -jar selenium-server-standalone-#.jar

*   Then when you create a session, be sure to pass the url to where your server is running.

        // This would be the url of the host running the server-standalone.jar
        $host = 'http://localhost:4444/wd/hub'; // this is the default
        $capabilities = array(WebDriverCapabilityType::BROWSER_NAME => 'firefox');
        $driver = new RemoteWebDriver($host, $capabilities);

*   The $capabilities array lets you specify (among other things) which browser to use. See https://code.google.com/p/selenium/wiki/DesiredCapabilities for more details.

## MORE INFORMATION

Check out the Selenium docs and wiki at http://docs.seleniumhq.org/docs/ and https://code.google.com/p/selenium/wiki

## CONTRIBUTING

We love to have your help to make php-webdriver better. Feel free to 

*   open an [issue](https://github.com/facebook/php-webdriver/issues) if you run into any problem. 
*   fork the project and submit [pull request](https://github.com/facebook/php-webdriver/pulls). Before the pull requests can be accepted, a [Contributors Licensing Agreement](http://developers.facebook.com/opensource/cla) must be signed. 

When you are going to contribute, please keep in mind that this webdriver client aims to be as close as possible to other languages Java/Ruby/Python/C#.
FYI, here is the overview of [the official Java API](http://selenium.googlecode.com/svn/trunk/docs/api/java/index.html?overview-summary.html)
