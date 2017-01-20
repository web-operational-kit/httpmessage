<?php

    /**
    * Web Operational Kit
    * The neither huger nor micro humble framework
    *
    * @copyright   All rights reserved 2015, Sébastien ALEXANDRE <sebastien@graphidev.fr>
    * @author      Sébastien ALEXANDRE <sebastien@graphidev.fr>
    * @license     BSD <license.txt>
    **/

    namespace WOK\HttpMessage;

    use WOK\Stream\Stream;
    use WOK\HttpMessage\Components\Headers;
    use WOK\HttpMessage\Components\CookiesCollection;


    /**
     * The Message abstracted class provide
     * common methods for both HTTP request and response.
    **/
    abstract class Message {

        /**
         * @var     string     $protocolVersion     HTTP Protocol version
        **/
        protected $protocolVersion = '1.1';

        /**
         * @var     Headers     $headers            Headers message collection
        **/
        protected $headers;

        /**
         * @var  CookiesCollection     $cookies    Cookies collection
        **/
        protected $cookies;

        /**
         * @var     Stream      $body               Body stream handler
        **/
        protected $body;


        /**
         * Instanciate Message object
         * @param Stream     $body       Body stream handler
         * @param Headers    $body       Body stream handler
        **/
        public function __construct(Stream $body, Headers $headers, $protocolVersion) {

            $this->setBody($body);
            $this->setHeaders($headers);
            $this->cookies = new CookiesCollection(array());

            $this->protocolVersion = $protocolVersion;

        }


        /**
         * Get the HTTP protocol version
         * @return     string       Returns the protocol version
        **/
        public function getProtocolVersion() {

            return $this->protocolVersion;

        }


        /**
         * Redefine the HTTP protocol version
         * @param      string       The HTTP protocol new version
         * @return     string       Returns the protocol version
        **/
        public function setProtocolVersion($version) {

            $this->protocolVersion = $version;

        }


        /**
         * Redefine the HTTP protocol version within a Message instance copy
         * @param      string       The HTTP protocol new version
         * @return     Message      Returns the Message instance copy with the new protocol version
        **/
        public function withProtocolVersion($version) {

            $message = clone $this;
            $message->setProtocolVersion($version);

            return $message;

        }


        /**
         * Retrieve message headers component
         * @return  Headers    Returns headers component
        **/
        public function getHeaders() {

            return $this->headers;

        }


        /**
         * Check whether a header exists or not
         * @param     string     $name        Header's name
         * @return    bool       Returns whether the header exists or not
        **/
        public function hasHeader($name) {

            return $this->headers->hasHeader($name);

        }


        /**
         * Retrieve a header value
         * @param     string     $name        Header's name
         * @param     mixed      $default     Default header's value
         * @return    mixed      Returns the header's value or the default one
        **/
        public function getHeader($name, $default = null) {

            return $this->headers->getHeader($name, $default);

        }


        /**
         * Retrieve a header value as a string
         * @param     string     $name        Header's name
         * @param     mixed      $default     Default header's value
         * @return    string     Returns the header's value or the default one
        **/
        public function getHeaderLine($name, $default = null) {

            return $this->headers->getHeaderLine($name, $default);

        }


        /**
         * Define a header value
         * @param     string     $name        Header's name
         * @param     string     $value       Header's value
        **/
        public function setHeader($name, $value) {

            $this->headers->setHeader($name, $value);

        }


        /**
         * Add a header's value
         * @param     string     $name        Header's name
         * @param     string     $value       Header's value
        **/
        public function addHeader($name, $value) {

            $this->headers->addHeader($name, $value);

        }


        /**
         * Define a header within a headers instance copy
         * @param     string     $name        Header's name
         * @param     string     $value       Header's value
         * @return    Headers    Returns a new headers instance
        **/
        public function withHeader($name, $value) {

            return $this->headers->withHeader($name, $value);

        }

        /**
         * Add a header's value within a headers instance copy
         * @param     string     $name        Header's name
         * @param     string     $value       Header's value
         * @return    Headers    Returns a new headers instance
        **/
        public function withAddedHeader($name, $value) {

            return $this->headers->withHeader($name, $value);

        }

        /**
         * Remove a header within a headers instance copy
         * @param     string     $name        Header's name
         * @return    Headers    Returns a new headers instance
        **/
        public function withoutHeader($name) {

            return $this->headers->without($name);

        }


        /**
         * Override headers list
         * @param array|Headers     $headers        Headers new list
        **/
        public function setHeaders($headers) {

            $this->headers = (is_array($headers) ? new Headers($headers) : $headers);

        }


        /**
         * Override headers list in a new instance
         * @param array|Headers     $headers        Headers new list
        **/
        public function withHeaders($headers) {

            $message = clone $this;
            $message->setHeaders($headers);

            return $message;

        }


        /**
         * Retrieve cookies collection
         * @return     CookiesCollection     Returns the cookies collection
        **/
        public function getCookies() {

            return $this->cookies;

        }


        /**
         * Define cookies collection
         * @param     CookiesCollection       $cookies           Cookies collection
        **/
        public function setCookies(CookiesCollection $cookies) {

            $this->cookies = $cookies;

        }


        /**
         * Define cookies collection within a message instane copy
         * @param     CookiesCollection       $cookies           Cookies collection
         * @return    Message                 Returns the message new instance containing cookies collection
        **/
        public function withCookies(CookiesCollection $cookies) {

            $message = clone $this;
            $message->setCookies($cookies);

            return $message;

        }


        /**
         * Retrieve message body
         * @return Body     Return body instance
        **/
        public function getBody() {

            return $this->body;

        }


        /**
         * Redefine message body
         * @param   Stream      Message new body
        **/
        public function setBody(Stream $body) {

            $this->body = $body;

        }


        /**
         * Redefine message body within a new instance
         * @param   Stream      Message new body
         * @return  Body        Returns a message instance copy with the new body
        **/
        public function withBody(Stream $body) {

            $message = clone $this;
            $message->setBody($body);

            return $message;

        }


        /**
         * Allow properties direct access
         * @param   string      $property       Property name
        **/
        public function __get($property) {

            if(!isset($this->$property))
                return false;

            return $this->$property;

        }


        /**
         * Duplicate properties on clone
        **/
        public function __clone() {

            $this->headers  = clone $this->headers;

        }


    }
