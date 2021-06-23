<?php

namespace Facebook\WebDriver\Exception;

class_exists(\PhpWebDriver\WebDriver\Exception\XPathLookupException::class);

if (\false) {
    /** @deprecated use "PhpWebDriver\WebDriver\Exception\XPathLookupException" instead. */
    class XPathLookupException extends \PhpWebDriver\WebDriver\Exception\XPathLookupException
    {
    }
}
