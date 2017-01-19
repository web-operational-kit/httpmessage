<?php

    use PHPUnit\Framework\TestCase;

    use \WOK\HttpMessage\Components\Cookie;
    use \WOK\HttpMessage\Components\CookiesCollection;

    class CookiesTest extends TestCase {

        /**
         * Test collection methods
         * ---
        **/
        public function testCookieCollectionMethods() {

            $cookies = new CookiesCollection();

            $cookie = $cookies->createCookie('cookieName', 'cookieValue');

            $this->assertInstanceOf('WOK\HttpMessage\Components\Cookie', $cookie, 'CookiesCollection::createCookie must return the cookie instance');
            $this->assertTrue($cookies->hasCookie('cookieName'), 'CookiesCollection::hasCookie must return true when getting a cookie that exists');
            $this->assertEquals($cookie, $cookies->getCookie('cookieName'), 'CookiesCollection::getCookie must return the right cookie instance');

        }

        /**
         * Test cookie `name` property methods
         * ---
        **/
        public function testCookieNameMethods() {

            $cookie = new Cookie('name');

            // Test getting
            $this->assertEquals('name', $cookie->getName(), 'Cookie::getName must return the cookie\'s name');

            // Test setting
            $cookie->setName('altName');
            $this->assertEquals('altName', $cookie->getName(), 'Cookie::setName must redefine cookie\'s name');

            // Test cloning
            $clone = $cookie->withName('cloneName');
            $this->assertNotEquals($clone, $cookie, 'Cookie::withName must return an other object instance');
            $this->assertEquals('cloneName', $clone->getName(), 'Cookie::withName must return an other object instance with the new name');

        }


        /**
         * Test cookie `value` property methods
         * ---
        **/
        public function testCookieValueMethods() {

            $cookie = new Cookie('name', 'value');

            // Test getting
            $this->assertEquals('value', $cookie->getValue(), 'Cookie::getValue must return the cookie\'s value');

            // Test setting
            $cookie->setValue('altValue');
            $this->assertEquals('altValue', $cookie->getValue(), 'Cookie::setValue must redefine cookie\'s value');

            // Test cloning
            $clone = $cookie->withValue('cloneValue');
            $this->assertNotEquals($clone, $cookie, 'Cookie::withValue must return an other object instance');
            $this->assertEquals('cloneValue', $clone->getValue(), 'Cookie::withValue must return an other object instance with the new value');

        }


        /**
         * Test cookie `maxAge` property methods
         * ---
        **/
        public function testCookieMaxAgeMethods() {

            $cookie = new Cookie('name', 'value', 3600);

            // Test getting
            $this->assertEquals(3600, $cookie->getMaxAge(), 'Cookie::getMaxAge must return the cookie\'s max age');

            // Test setting
            $cookie->setMaxAge(1234);
            $this->assertEquals(1234, $cookie->getMaxAge(), 'Cookie::setMaxAge must redefine cookie\'s max age');

            // Test cloning
            $clone = $cookie->withMaxAge(4567);
            $this->assertNotEquals($clone, $cookie, 'Cookie::withMaxAge must return an other object instance');
            $this->assertEquals(4567, $clone->getMaxAge(), 'Cookie::withMaxAge must return an other object instance with the new max age');

        }

        /**
         * Test cookie `domain` property methods
         * ---
        **/
        public function testCookieDomainMethods() {

            $cookie = new Cookie('name', 'value', 3600, 'domain.tld');

            // Test getting
            $this->assertEquals('domain.tld', $cookie->getDomain(), 'Cookie::getDomain must return the cookie\'s domain');

            // Test setting
            $cookie->setDomain('alt.domain.tld');
            $this->assertEquals('alt.domain.tld', $cookie->getDomain(), 'Cookie::setDomain must redefine cookie\'s domain');

            // Test cloning
            $clone = $cookie->withDomain('clone.domain.tld');
            $this->assertNotEquals($clone, $cookie, 'Cookie::withDomain must return an other object instance');
            $this->assertEquals('clone.domain.tld', $clone->getDomain(), 'Cookie::withDomain must return an other object instance with the new domain');

        }

        /**
         * Test cookie `path` property methods
         * ---
        **/
        public function testCookiePathMethods() {

            $cookie = new Cookie('name', 'value', 3600, 'domain.tld', '/path/limited');

            // Test getting
            $this->assertEquals('/path/limited', $cookie->getPath(), 'Cookie::getPath must return the cookie\'s path');

            // Test setting
            $cookie->setPath('/path/limited/alt');
            $this->assertEquals('/path/limited/alt', $cookie->getPath(), 'Cookie::setPath must redefine cookie\'s path');

            // Test cloning
            $clone = $cookie->withPath('/path/clone');
            $this->assertNotEquals($clone, $cookie, 'Cookie::withPath must return an other object instance');
            $this->assertEquals('/path/clone', $clone->getPath(), 'Cookie::withPath must return an other object instance with the new path');

        }


        /**
         * Test cookie `secure` property methods
         * ---
        **/
        public function testCookieSecureMethods() {

            $cookie = new Cookie('name', 'value', 3600, 'domain.tld', '/path/limited', null);

            // Test getting
            $this->assertEquals(null, $cookie->getSecure(), 'Cookie::getSecure must return the cookie\'s secure (should be null)');

            // Test setting
            $cookie->setSecure(true);
            $this->assertTrue($cookie->getSecure(), 'Cookie::setSecure must redefine cookie\'s secure');
            $cookie->setSecure('abcd');
            $this->assertTrue(is_bool($secure = $cookie->getSecure()) && $secure, 'Cookie::setSecure must translate any value to a boolean one');

            // Test cloning
            $clone = $cookie->withSecure(false);
            $this->assertNotEquals($clone, $cookie, 'Cookie::withSecure must return an other object instance');
            $this->assertEquals(false, $clone->getSecure(), 'Cookie::withSecure must return an other object instance with the new secure');

        }


        /**
         * Test cookie `httpOnly` property methods
         * ---
        **/
        public function testCookieHttpOnlyMethods() {

            $cookie = new Cookie('name', 'value', 3600, 'domain.tld', '/path/limited', null, null);

            // Test getting
            $this->assertEquals(null, $cookie->getHttpOnly(), 'Cookie::getHttpOnly must return the cookie\'s httpOnly (should be null)');

            // Test setting
            $cookie->setHttpOnly(true);
            $this->assertTrue($cookie->getHttpOnly(), 'Cookie::setHttpOnly must redefine cookie\'s httpOnly');
            $cookie->setHttpOnly('abcd');
            $this->assertTrue(is_bool($httpOnly = $cookie->getHttpOnly()) && $httpOnly, 'Cookie::setHttpOnly must translate any value to a boolean one');

            // Test cloning
            $clone = $cookie->withHttpOnly(false);
            $this->assertNotEquals($clone, $cookie, 'Cookie::withHttpOnly must return an other object instance');
            $this->assertEquals(false, $clone->getHttpOnly(), 'Cookie::withHttpOnly must return an other object instance with the new httpOnly');

        }

        /**
         * Test cookie `sameSite` property methods
         * ---
        **/
        public function testCookieSameSiteMethods() {

            $cookie = new Cookie('name', 'value', 3600, 'domain.tld', '/path/limited', null, null, null);

            // Test getting
            $this->assertEquals(null, $cookie->getSameSite(), 'Cookie::getSameSite must return the cookie\'s sameSite (should be null)');

            // Test setting
            $cookie->setSameSite('Strict');
            $this->assertEquals('Strict', $cookie->getSameSite(), 'Cookie::setSameSite must redefine cookie\'s sameSite');

            // Test cloning
            $clone = $cookie->withSameSite('Lax');
            $this->assertNotEquals($clone, $cookie, 'Cookie::withSameSite must return an other object instance');
            $this->assertEquals('Lax', $clone->getSameSite(), 'Cookie::withSameSite must return an other object instance with the new sameSite');

        }


        /**
         * Test cookie `sameSite` property methods
         * ---
        **/
        public function testCookie__ToStringMethod() {

            $cookie = new Cookie('cookieName', 'cookieValue', 3600, 'domain.tld', '/path/limited', true, true, 'Strict');
            $expires = date('r', time()+3600);
            $string = "cookieName=cookieValue; Max-Age=3600; Expires=$expires; Domain=domain.tld; Path=/path/limited; Secure; HttpOnly; SameSite=Strict";

            $this->assertEquals($string, (string) $cookie, 'Cookie::__toString must return the cookie as HTTP header string (without header name)');

        }

        // cookieName=cookieValue; Max-Age=3600; Expires=Thu, 19 Jan 2017 15:23:30 +0000; Domain=domain.tld; Path=/path/limited; Strict; HttpOnly; SameSite=Strict
        // cookieName=cookieValue; Max-Age=3600; Expires=Thu, 19 Jan 2017 15:23:30 +0000; Domain=domain.tld; Path=/path/limited; Secure; HttpOnly; SameSite=Strict


    }
