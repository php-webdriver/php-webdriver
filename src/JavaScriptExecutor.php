<?php

namespace Facebook\WebDriver;

class_exists(\PhpWebDriver\WebDriver\JavaScriptExecutor::class);

if (\false) {
    /** @deprecated use "PhpWebDriver\WebDriver\JavaScriptExecutor" instead. */
    class JavaScriptExecutor extends \PhpWebDriver\WebDriver\JavaScriptExecutor
    {
    }
}
