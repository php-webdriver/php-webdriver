<?php

namespace PhpWebDriver\WebDriver\Exception;

/**
 * Navigation caused the user agent to hit a certificate warning, which is usually the result of an expired
 * or invalid TLS certificate.
 */
class InsecureCertificateException extends WebDriverException
{
}

class_alias(\PhpWebDriver\WebDriver\Exception\InsecureCertificateException::class, \Facebook\WebDriver\Exception\InsecureCertificateException::class);
