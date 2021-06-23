<?php

namespace Facebook\WebDriver\Exception;

class_exists(\PhpWebDriver\WebDriver\Exception\InsecureCertificateException::class);

if (\false) {
    /** @deprecated use "PhpWebDriver\WebDriver\Exception\InsecureCertificateException" instead. */
    class InsecureCertificateException extends \PhpWebDriver\WebDriver\Exception\InsecureCertificateException
    {
    }
}
