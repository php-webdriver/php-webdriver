<?php

namespace Facebook\WebDriver;

use PHPUnit\Framework\TestCase;

/**
 * @covers \Facebook\WebDriver\Cookie
 */
class CookieTest extends TestCase
{
    public function testShouldSetAllProperties()
    {
        $cookie = new Cookie('cookieName', 'someValue');
        $cookie->setPath('/bar');
        $cookie->setDomain('foo.com');
        $cookie->setExpiry(1485388387);
        $cookie->setSecure(true);
        $cookie->setHttpOnly(true);
        $cookie->setSameSite('Lax');

        $this->assertSame('cookieName', $cookie->getName());
        $this->assertSame('someValue', $cookie->getValue());
        $this->assertSame('/bar', $cookie->getPath());
        $this->assertSame('foo.com', $cookie->getDomain());
        $this->assertSame(1485388387, $cookie->getExpiry());
        $this->assertTrue($cookie->isSecure());
        $this->assertTrue($cookie->isHttpOnly());
        $this->assertSame('Lax', $cookie->getSameSite());

        return $cookie;
    }

    /**
     * @depends testShouldSetAllProperties
     * @param Cookie $cookie
     */
    public function testShouldBeConvertibleToArray(Cookie $cookie)
    {
        $this->assertSame(
            [
                'name' => 'cookieName',
                'value' => 'someValue',
                'path' => '/bar',
                'domain' => 'foo.com',
                'expiry' => 1485388387,
                'secure' => true,
                'httpOnly' => true,
                'sameSite' => 'Lax',
            ],
            $cookie->toArray()
        );
    }

    /**
     * Test that there are no null values in the cookie array.
     *
     * Both JsonWireProtocol and w3c protocol say to leave an entry off
     * rather than having a null value.
     *
     * https://github.com/SeleniumHQ/selenium/wiki/JsonWireProtocol
     * https://w3c.github.io/webdriver/#add-cookie
     */
    public function testShouldNotContainNullValues()
    {
        $cookie = new Cookie('cookieName', 'someValue');

        $cookie->setHttpOnly(null);
        $cookie->setPath(null);
        $cookie->setSameSite(null);
        $cookieArray = $cookie->toArray();

        foreach ($cookieArray as $key => $value) {
            $this->assertNotNull($value, $key . ' should not be null');
        }
    }

    /**
     * @depends testShouldSetAllProperties
     * @param Cookie $cookie
     */
    public function testShouldProvideArrayAccessToProperties(Cookie $cookie)
    {
        $this->assertSame('cookieName', $cookie['name']);
        $this->assertSame('someValue', $cookie['value']);
        $this->assertSame('/bar', $cookie['path']);
        $this->assertSame('foo.com', $cookie['domain']);
        $this->assertSame(1485388387, $cookie['expiry']);
        $this->assertTrue($cookie['secure']);
        $this->assertTrue($cookie['httpOnly']);
        $this->assertSame('Lax', $cookie['sameSite']);

        $cookie->offsetSet('domain', 'bar.com');
        $this->assertSame('bar.com', $cookie['domain']);
        $cookie->offsetUnset('domain');
        $this->assertArrayNotHasKey('domain', $cookie);
    }

    public function testShouldBeCreatableFromAnArrayWithBasicValues()
    {
        $sourceArray = [
            'name' => 'cookieName',
            'value' => 'someValue',
        ];

        $cookie = Cookie::createFromArray($sourceArray);

        $this->assertSame('cookieName', $cookie['name']);
        $this->assertSame('someValue', $cookie['value']);

        $this->assertArrayNotHasKey('path', $cookie);
        $this->assertNull($cookie['path']);
        $this->assertNull($cookie->getPath());

        $this->assertArrayNotHasKey('domain', $cookie);
        $this->assertNull($cookie['domain']);
        $this->assertNull($cookie->getDomain());

        $this->assertArrayNotHasKey('expiry', $cookie);
        $this->assertNull($cookie['expiry']);
        $this->assertNull($cookie->getExpiry());

        $this->assertArrayNotHasKey('secure', $cookie);
        $this->assertNull($cookie['secure']);
        $this->assertNull($cookie->isSecure());

        $this->assertArrayNotHasKey('httpOnly', $cookie);
        $this->assertNull($cookie['httpOnly']);
        $this->assertNull($cookie->isHttpOnly());

        $this->assertArrayNotHasKey('sameSite', $cookie);
        $this->assertNull($cookie['sameSite']);
        $this->assertNull($cookie->getSameSite());
    }

    public function testShouldBeCreatableFromAnArrayWithAllValues()
    {
        $sourceArray = [
            'name' => 'cookieName',
            'value' => 'someValue',
            'path' => '/bar',
            'domain' => 'foo',
            'expiry' => 1485388333,
            'secure' => false,
            'httpOnly' => false,
            'sameSite' => 'Lax',
        ];

        $cookie = Cookie::createFromArray($sourceArray);

        $this->assertSame('cookieName', $cookie['name']);
        $this->assertSame('someValue', $cookie['value']);
        $this->assertSame('/bar', $cookie['path']);
        $this->assertSame('foo', $cookie['domain']);
        $this->assertSame(1485388333, $cookie['expiry']);
        $this->assertFalse($cookie['secure']);
        $this->assertFalse($cookie['httpOnly']);
        $this->assertSame('Lax', $cookie['sameSite']);
    }

    /**
     * @dataProvider provideInvalidCookie
     * @param string $name
     * @param string $value
     * @param string $domain
     * @param string $expectedMessage
     */
    public function testShouldValidateCookieOnConstruction($name, $value, $domain, $expectedMessage)
    {
        if ($expectedMessage) {
            $this->expectException(\InvalidArgumentException::class);
            $this->expectExceptionMessage($expectedMessage);
        }

        $cookie = new Cookie($name, $value);
        if ($domain !== null) {
            $cookie->setDomain($domain);
        }

        $this->assertInstanceOf(Cookie::class, $cookie);
    }

    /**
     * @return array[]
     */
    public function provideInvalidCookie()
    {
        return [
            // $name, $value, $domain, $expectedMessage
            'name cannot be empty' => ['', 'foo', null, 'Cookie name should be non-empty'],
            'name cannot be null' => [null, 'foo', null, 'Cookie name should be non-empty'],
            'name cannot contain semicolon' => ['name;semicolon', 'foo', null, 'Cookie name should not contain a ";"'],
            'value could be empty string' => ['name', '', null, null],
            'value cannot be null' => ['name', null, null, 'Cookie value is required when setting a cookie'],
            'domain cannot containt port' => [
                'name',
                'value',
                'localhost:443',
                'Cookie domain "localhost:443" should not contain a port',
            ],
            'cookie with valid values' => ['name', 'value', '*.localhost', null],
        ];
    }
}
