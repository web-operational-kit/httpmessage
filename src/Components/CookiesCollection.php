<?php

    /**
     * Web Operational Kit
     * The neither huger nor micro humble framework
     *
     * @copyright   All rights reserved 2015, Sébastien ALEXANDRE <sebastien@graphidev.fr>
     * @author      Sébastien ALEXANDRE <sebastien@graphidev.fr>
     * @license     BSD <license.txt>
    **/

    namespace WOK\HttpMessage\Components;

    use \WOK\Collection\Collection;

    /**
     * The Cookies class provide a cookies
     * collection manager interface.
    **/
    class CookiesCollection extends Collection {


        /**
         * Instanciate a Cookies collection
         * @param array     $cookies        Initial cookies collection
        **/
        public function __construct(array $cookies = array()) {

            foreach($cookies as $name => $value) {
                $this->createCookie($name, $value);
            }

            $this->rewind();

        }

        /**
         * Get a cookie
         * @param   string    $name       Cookie name
         * @return  Cookie    Returns a cookie object
        **/
        public function getCookie($name) {

            if(!$this->has($name)) {
                throw new \InvalidArgumentException('Undefined cookie with name `'.$name.'`')
            }

            return $this->get($name);

        }

        /**
         * Check if a cookie exists
         * @return bool     Returns wether the cookie exists or not
        **/
        public function hasCookie($name) {

            return $this->has($name);

        }


        /**
         * Assign an all new cookie
         * @param   Cookie              $cookie         Cookie instance
        **/
        public function setCookie(Cookie $cookie) {

            $this->set($cookie->getName(), $cookie);

        }

        /**
         * Create a new cookie
         * @param   string              $name              Cookie's name
         * @param   string|array        $values            Cookie's values
        **/
        public function createCookie($name, $value = '', $maxAge = null, $domain = '', $path = '', $secure = null, $httpOnly = null, $sameSite = null) {

            $cookie = new Cookie($name, $value, $maxAge, $domain, $path, $secure, $httpOnly, $sameSite);

            $this->setCookie($cookie);

            return $cookie;

        }


        /**
         * Get a cookies collection object copy with the new cookie definition.
         * @param   Cookie              $cookie         Cookie instance
         * @return  self
        **/
        public function withCookie(Cookie $cookie) {

            $cookies = clone $this;
            $cookies->setCookie($name, $value);

            return $cookies;

        }

        /**
         * Remove a defined cookie
         * @param   string              $name              Cookie's name
        **/
        public function removeCookie($name) {

            $this->remove($name);

        }


        /**
         * Get a new instance of the collection object
         * without a defined cookie.
         * @param   string      $name       Cookie's name
         * @return  self
        **/
        public function withoutCookie($name) {

            $cookies = clone $this;
            $cookies->remove($name);

            return $cookies;

        }

    }
