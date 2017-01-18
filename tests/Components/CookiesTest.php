<?php

    use PHPUnit\Framework\TestCase;

    use \WOK\HttpMessage\Components\Cookie;
    use \WOK\HttpMessage\Components\CookiesCollection;

    class CookiesTest extends TestCase {

        /**
         * Test collection methods
         * ---
        **/
        public function testCollectionMethods() {

            $cookie = new CookiesCollection();

            // $cookie->setCookie($cookie);
            // $cookie->createCookie($name, $value, ...);

        }

    }
