<?php

    namespace WOK\HttpMessage\Components;

    class Cookie {

        /**
         * Cookie's name
         * @var     string     $name
        **/
        protected $name     = '';

        /**
         * Cookie's value
         * @var     string     $value
        **/
        protected $value    = '';

        /**
         * Cookie's max age
         * @var     integer    $maxAge
        **/
        protected $maxAge   = null;

        /**
         * Cookie's accepted domain
         * @var     string     $domain
        **/
        protected $domain   = '';

        /**
         * Cookie's accepted path prefix
         * @var     string     $path
        **/
        protected $path     = '';

        /**
         * Cookie must be sent only within secure environments
         * @var     boolean    $secure
        **/
        protected $secure   = null;

        /**
         * Cookie must be sent only within direct HTTP requests
         * @var     boolean    $httpOnly
        **/
        protected $httpOnly = null;

        /**
         * Cookie must be sent only to the site sender
         * @var     string     $secure
        **/
        protected $sameSite = null;


        /**
         * Instanciate a new cookie
         * @param     string         $name         Cookie's name
         * @param     string         $value        Cookie's value
         * @param     integer        $maxAge       Cookie's max age (used to generate the Expires property)
         * @param     string         $domain       Accepted domain
         * @param     string         $path         Accepted path prefix
         * @param     boolean        $secure       Should send only when connection is secured
         * @param     boolean        $httpOnly     Prevent front to access to the cookie
         * @param     string         $sameSite     Sending conditions over network
        **/
        public function __construct($name, $value = '', $maxAge = null, $domain = '', $path = '', $secure = null, $httpOnly = null, $sameSite = null) {

            if(empty($name)) {
                throw \InvalidArgumentException('Cookie\'s name must be set and can not be empty');
            }

            $this->name     = $name;
            $this->value    = $value;
            $this->maxAge   = $maxAge;
            $this->domain   = $domain;
            $this->path     = $path;
            $this->secure   = $secure;
            $this->httpOnly = $httpOnly;
            $this->sameSite = $sameSite;

        }


        /**
         * Define cookie name
         * @param     string     $name     Cookie's name
        **/
        public function setName($name) {

            $this->name = $name;

        }

        /**
         * Define cookie name whithin a cookie object copy
         * @param     string     $name     Cookie's name
         * @return    Cookie     Returns the cookie object with the new name
        **/
        public function withName($name) {

            $cookie = clone $this;
            $cookie->setName($name);

            return $cookie;

        }

        /**
         * Get the cookie name
        **/
        public function getName() {

            return $this->name;

        }


        /**
         * Define cookie value
         * @param     string     $value     Cookie's value
        **/
        public function setValue($value) {

            $this->value = $value;

        }

        /**
         * Define cookie value whithin a cookie object copy
         * @param     string     $value     Cookie's value
         * @return    Cookie     Returns the cookie object with the new value
        **/
        public function withValue($value) {

            $cookie = clone $this;
            $cookie->setValue($value);

            return $cookie;

        }

        /**
         * Get the cookie value
        **/
        public function getValue() {

            return $this->value;

        }


        /**
         * Define Max-Age (will be used to set `Expires` property)
         * @param     integer     $time     Max-age time in seconds
        **/
        public function setMaxAge($time) {

            return $this->maxAge = $time;

        }

        /**
         * Define Max-Age (will be used to set `Expires` property) within a cookie object copy
         * @param     integer     $time     Max-age time in seconds
         * @return    Cookie     Returns the cookie object with the new `max-age` value
        **/
        public function withMaxAge($time) {

            $cookie = clone $this;
            $cookie->setMaxAge($time);

            return $cookie;

        }

        /**
         * Retrieve cookie max age
         * @param     integer     Returns the cookie max-age in seconds
        **/
        public function getMaxAge() {

            return $this->maxAge;

        }


        /**
         * Define cookie working domain
         * @param     string     $domain     Working domain
        **/
        public function setDomain($domain) {

            $this->domain = $domain;

        }


        /**
         * Define cookie working domain within a Cookie object copy
         * @param     string     $domain     Working domain
         * @return    Cookie     Returns the cookie object with the new `domain` value
        **/
        public function withDomain($domain) {

            $cookie = clone $this;
            $cookie->setDomain($domain);

            return $cookie;

        }


        /**
         * Retrieve cookie domain
         * @param     integer     Returns the cookie domain
        **/
        public function getDomain() {

            return $this->domain;

        }


        /**
         * Define cookie working path
         * @param     string     $path     Working path
        **/
        public function setPath($path) {

            $this->path = $path;

        }

        /**
         * Define cookie working path within a Cookie object copy
         * @param     string     $path     Working path
         * @return    Cookie     Returns the cookie object with the new `path` value
        **/
        public function withPath($path) {

            $cookie = clone $this;
            $cookie->setPath($path);

            return $cookie;

        }

        /**
         * Retrieve cookie path
         * @return     integer     Returns the cookie path
        **/
        public function getPath() {

            return $this->path;

        }


        /**
         * Cookie must or not be sent over secure network
         * @param     boolean     $secure     Keep cookie secure only
        **/
        public function setSecure($secure) {

            $this->secure = ($secure ? true : false);

        }

        /**
         * Cookie must or not be sent over secure network within a Cookie object copy
         * @param     string     $path     Working path
         * @return    Cookie     Returns the cookie object with the new `secure` value
        **/
        public function withSecure($secure) {

            $cookie = clone $this;
            $cookie->setSecure($secure);

            return $cookie;

        }

        /**
         * Cookie must or not be sent over secure network, within a cookie object copy
         * @return     integer     Returns the cookie object copy
        **/
        public function getSecure() {

            return $this->secure;

        }


        /**
         * Cookie must or not be sent over direct requests only
         * @param     boolean     $httpOnly     Keep cookie secure only
        **/
        public function setHttpOnly($httpOnly) {

            $this->httpOnly = ($httpOnly ? true : false);

        }

        /**
         * Cookie must or not be sent over secure network within a Cookie object copy
         * @param     string     $httpOnly     Working path
         * @return    Cookie     Returns the cookie object with the new `secure` value
        **/
        public function withHttpOnly($httpOnly) {

            $cookie = clone $this;
            $cookie->setHttpOnly($httpOnly);

            return $cookie;

        }

        /**
         * Cookie must or not be sent over secure network, within a cookie object copy
         * @return     integer     Returns whether the cookie as Secure property or not
        **/
        public function getHttpOnly() {

            return $this->httpOnly;

        }


        /**
         * Define cookie `SameSite` property mode (Lax|Strict)
         * @param     boolean     $sameSite     Cookie SameSite property mode
        **/
        public function setSameSite($sameSite) {

            $this->sameSite = $sameSite;

        }

        /**
         * Define cookie `SameSite` property mode (Lax|Strict) within a Cookie object copy
         * @param     string     $sameSite     Cookie SameSite property mode
         * @return    Cookie     Returns the cookie object with the new `SameSite` value
        **/
        public function withSameSite($sameSite) {

            $cookie = clone $this;
            $cookie->setSameSite($sameSite);

            return $cookie;

        }

        /**
         * Retrieve the cooke SameSite property mode
         * @return     integer     Returns the cookie `sameSite` value
        **/
        public function getSameSite() {

            return $this->sameSite;

        }


        /**
         * Output the cookie object as string
         * @return    string     Returns the cookie mathing the HTTP `Set-Cookie` header format
        **/
        public function __toString() {

            $string = $this->name.'='.urlencode($this->value);

            if(!empty($this->maxAge)) {
                $string .= '; Max-Age='.$this->maxAge;
                $string .= '; Expires='.date('r', time() + $this->maxAge);
            }

            if(!empty($this->domain)) {
                $string .= '; Domain='.$this->domain;
            }

            if(!empty($this->path)) {
                $string .= '; Path='.$this->path;
            }

            if(!is_null($this->secure) && $this->secure) {
                $string .= '; Secure';
            }

            if(!is_null($this->httpOnly) && $this->httpOnly) {
                $string .= '; HttpOnly';
            }

            if(!empty($this->sameSite)) {
                $string .= '; SameSite='.$this->sameSite;
            }

            return $string;

        }

    }

    $user = [
        'locale'    => 'fr_FR',
        'tracking'  => false,
    ];
