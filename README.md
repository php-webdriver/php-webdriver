php-webdriver -- A very thin wrapper of WebDriver
=================================================

## DESCRIPTION

This client aims to be as this as possible, abusing the dynamic nature of PHP to allow almost all API calls to be a direct transformation of what is defined in the WebDriver protocol itself.

Most clients require you to first read the protocol to see what's possible, then study the client itself to see how to call it.  This hopes to eliminate the latter step, and invites you to rely almost exclusively on http://code.google.com/p/selenium/wiki/JsonWireProtocol

## SIMPLE EXAMPLES

* In order to call http://code.google.com/p/selenium/wiki/JsonWireProtocol#/session/:sessionId/orientation we'd say:
<code> $web_driver->session()->orientation(array('orientation' => 'LANDSCAPE')) </code>


