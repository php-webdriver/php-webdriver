<?php declare(strict_types=1);

namespace Facebook\WebDriver;

use Facebook\WebDriver\Exception\NoSuchCookieException;

/**
 * @covers \Facebook\WebDriver\WebDriverOptions
 */
class WebDriverOptionsCookiesTest extends WebDriverTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->driver->get($this->getTestPageUrl(TestPage::INDEX));
    }

    public function testShouldSetGetAndDeleteCookies(): void
    {
        $cookie1 = new Cookie('cookie1', 'cookie1Value');
        $cookie2 = new Cookie('cookie2', 'cookie2Value');

        // Verify initial state - no cookies are present
        $this->assertSame([], $this->driver->manage()->getCookies());

        // Add cookie1
        $this->driver->manage()->addCookie($cookie1);

        // get all cookies
        $cookiesWithOneCookie = $this->driver->manage()->getCookies();
        $this->assertCount(1, $cookiesWithOneCookie);
        $this->assertContainsOnlyInstancesOf(Cookie::class, $cookiesWithOneCookie);
        $this->assertSame('cookie1', $cookiesWithOneCookie[0]->getName());
        $this->assertSame('cookie1Value', $cookiesWithOneCookie[0]->getValue());
        $this->assertSame('/', $cookiesWithOneCookie[0]->getPath());
        $this->assertSame('localhost', $cookiesWithOneCookie[0]->getDomain());

        // Add cookie2
        $this->driver->manage()->addCookie($cookie2);

        // get all cookies
        $cookiesWithTwoCookies = $this->driver->manage()->getCookies();

        $this->assertCount(2, $cookiesWithTwoCookies);
        $this->assertContainsOnlyInstancesOf(Cookie::class, $cookiesWithTwoCookies);

        // normalize received cookies (their order is arbitrary)
        $normalizedCookies = [
            $cookiesWithTwoCookies[0]->getName() => $cookiesWithTwoCookies[0]->getValue(),
            $cookiesWithTwoCookies[1]->getName() => $cookiesWithTwoCookies[1]->getValue(),
        ];
        ksort($normalizedCookies);
        $this->assertSame(['cookie1' => 'cookie1Value', 'cookie2' => 'cookie2Value'], $normalizedCookies);

        // getCookieNamed()
        $onlyCookieOne = $this->driver->manage()->getCookieNamed('cookie1');
        $this->assertInstanceOf(Cookie::class, $onlyCookieOne);
        $this->assertSame('cookie1', $onlyCookieOne->getName());
        $this->assertSame('cookie1Value', $onlyCookieOne->getValue());

        // deleteCookieNamed()
        $this->driver->manage()->deleteCookieNamed('cookie1');
        $cookiesWithOnlySecondCookie = $this->driver->manage()->getCookies();
        $this->assertCount(1, $cookiesWithOnlySecondCookie);
        $this->assertSame('cookie2', $cookiesWithOnlySecondCookie[0]->getName());

        // getting non-existent cookie should throw an exception in W3C mode but return null in JsonWire mode
        if (self::isW3cProtocolBuild()) {
            try {
                $noSuchCookieExceptionThrown = false;
                $this->driver->manage()->getCookieNamed('cookie1');
            } catch (NoSuchCookieException $e) {
                $noSuchCookieExceptionThrown = true;
            } finally {
                $this->assertTrue($noSuchCookieExceptionThrown, 'NoSuchCookieException was not thrown');
            }
        } else {
            $this->assertNull($this->driver->manage()->getCookieNamed('cookie1'));
        }

        // deleting non-existent cookie shod not throw an error
        $this->driver->manage()->deleteCookieNamed('cookie1');

        // Add cookie3
        $this->driver->manage()->addCookie($cookie1);
        $this->assertCount(2, $this->driver->manage()->getCookies());

        // Delete all cookies
        $this->driver->manage()->deleteAllCookies();

        $this->assertSame([], $this->driver->manage()->getCookies());
    }
}
