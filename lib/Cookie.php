<?php
// Copyright 2004-present Facebook. All Rights Reserved.
//
// Licensed under the Apache License, Version 2.0 (the "License");
// you may not use this file except in compliance with the License.
// You may obtain a copy of the License at
//
//     http://www.apache.org/licenses/LICENSE-2.0
//
// Unless required by applicable law or agreed to in writing, software
// distributed under the License is distributed on an "AS IS" BASIS,
// WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
// See the License for the specific language governing permissions and
// limitations under the License.

namespace Facebook\WebDriver;

use InvalidArgumentException;

/**
 * Set values of an cookie.
 *
 * Implements ArrayAccess for backwards compatibility.
 *
 * @see https://w3c.github.io/webdriver/webdriver-spec.html#cookies
 */
class Cookie implements \ArrayAccess
{
    /** @var array */
    protected $cookie = [
        'name' => null,
        'value' => null,
        'path' => null,
        'domain' => null,
        'expiry' => null,
        'secure' => null,
        'httpOnly' => null,
    ];

    /**
     * @param string $name The name of the cookie; may not be null or an empty string.
     * @param string $value The cookie value; may not be null.
     */
    public function __construct($name, $value)
    {
        $this->validateCookieName($name);
        $this->validateCookieValue($value);

        $this->cookie['name'] = $name;
        $this->cookie['value'] = $value;
    }

    /**
     * @param array $cookieArray
     * @return Cookie
     */
    public static function createFromArray(array $cookieArray)
    {
        $cookie = new self($cookieArray['name'], $cookieArray['value']);

        if (isset($cookieArray['path'])) {
            $cookie->setPath($cookieArray['path']);
        }
        if (isset($cookieArray['domain'])) {
            $cookie->setDomain($cookieArray['domain']);
        }
        if (isset($cookieArray['expiry'])) {
            $cookie->setExpiry($cookieArray['expiry']);
        }
        if (isset($cookieArray['secure'])) {
            $cookie->setSecure($cookieArray['secure']);
        }
        if (isset($cookieArray['httpOnly'])) {
            $cookie->setHttpOnly($cookieArray['httpOnly']);
        }

        return $cookie;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->cookie['name'];
    }

    /**
     * @return string
     */
    public function getValue()
    {
        return $this->cookie['value'];
    }

    /**
     * The path the cookie is visible to. Defaults to "/" if omitted.
     *
     * @param string $path
     */
    public function setPath($path)
    {
        $this->cookie['path'] = $path;
    }

    /**
     * @return string
     */
    public function getPath()
    {
        return $this->cookie['path'];
    }

    /**
     * The domain the cookie is visible to. Defaults to the current browsing context's document's URL domain if omitted.
     *
     * @param string $domain
     */
    public function setDomain($domain)
    {
        if (mb_strpos($domain, ':') !== false) {
            throw new InvalidArgumentException(sprintf('Cookie domain "%s" should not contain a port', $domain));
        }

        $this->cookie['domain'] = $domain;
    }

    /**
     * @return string
     */
    public function getDomain()
    {
        return $this->cookie['domain'];
    }

    /**
     * The cookie's expiration date, specified in seconds since Unix Epoch.
     *
     * @param int $expiry
     */
    public function setExpiry($expiry)
    {
        $this->cookie['expiry'] = (int) $expiry;
    }

    /**
     * @return int
     */
    public function getExpiry()
    {
        return $this->cookie['expiry'];
    }

    /**
     * Whether this cookie requires a secure connection (https). Defaults to false if omitted.
     *
     * @param bool $secure
     */
    public function setSecure($secure)
    {
        $this->cookie['secure'] = $secure;
    }

    /**
     * @return bool
     */
    public function isSecure()
    {
        return $this->cookie['secure'];
    }

    /**
     * Whether the cookie is an HTTP only cookie. Defaults to false if omitted.
     *
     * @param bool $httpOnly
     */
    public function setHttpOnly($httpOnly)
    {
        $this->cookie['httpOnly'] = $httpOnly;
    }

    /**
     * @return bool
     */
    public function isHttpOnly()
    {
        return $this->cookie['httpOnly'];
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return $this->cookie;
    }

    public function offsetExists($offset)
    {
        return isset($this->cookie[$offset]);
    }

    public function offsetGet($offset)
    {
        return $this->cookie[$offset];
    }

    public function offsetSet($offset, $value)
    {
        $this->cookie[$offset] = $value;
    }

    public function offsetUnset($offset)
    {
        unset($this->cookie[$offset]);
    }

    /**
     * @param string $name
     */
    protected function validateCookieName($name)
    {
        if ($name === null || $name === '') {
            throw new InvalidArgumentException('Cookie name should be non-empty');
        }

        if (mb_strpos($name, ';') !== false) {
            throw new InvalidArgumentException('Cookie name should not contain a ";"');
        }
    }

    /**
     * @param string $value
     */
    protected function validateCookieValue($value)
    {
        if ($value === null) {
            throw new InvalidArgumentException('Cookie value is required when setting a cookie');
        }
    }
}
