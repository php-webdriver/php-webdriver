<?php

namespace Facebook\WebDriver\Remote;

use Facebook\WebDriver\Remote\Translator\JsonWireProtocolTranslator;
use Facebook\WebDriver\Remote\Translator\W3CProtocolTranslator;
use Facebook\WebDriver\Remote\Translator\WebDriverProtocolTranslator;

class WebDriverTranslatorFactory
{
    /**
     * @param WebDriverDialect $dialect
     * @return WebDriverProtocolTranslator
     */
    public static function createByDialect(WebDriverDialect $dialect)
    {
        if ($dialect->isW3C()) {
            return new W3CProtocolTranslator();
        }
        return new JsonWireProtocolTranslator();
    }
}
