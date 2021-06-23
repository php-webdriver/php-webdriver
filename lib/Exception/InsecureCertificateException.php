<?php

namespace Facebook\WebDriver\Exception;

/**
 * Navigation caused the user agent to hit a certificate warning, which is usually the result of an expired
 * or invalid TLS certificate.
 */
class InsecureCertificateException extends WebDriverException
{
}

class_alias('Facebook\WebDriver\Exception\InsecureCertificateException', 'PhpWebDriver\Exception\InsecureCertificateException');
