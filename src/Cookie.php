<?php

namespace Facebook\WebDriver;

class_exists(\PhpWebDriver\WebDriver\Cookie::class);

if (\false) {
    /** @deprecated use "PhpWebDriver\WebDriver\Cookie" instead. */
    class Cookie extends \PhpWebDriver\WebDriver\Cookie
    {
    }
}
