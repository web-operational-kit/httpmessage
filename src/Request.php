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

    use \WOK\Uri\Uri;
    use \WOK\Stream\Stream;
    use \WOK\HttpMessage\Components\Headers;

    /**
     * The Request class provide an interface
     * to manage an HTTP request
    **/
    class Request extends Message {

        /**
         * @var string      $method     HTTP method name
        **/
        protected $method;


        /**
         * @var Uri         $uri        \WOK\Uri\Uri instance
        **/
        protected $uri;


        /**
         * Instanciate a new Request object
         * @param   string              $method             Response HTTP code associated notice
         * @param   string|Uri          $uri                Response HTTP code associated notice
         * @param   array               $headers            Response headers
         * @param   string|Stream       $body               Response body
         * @param   string              $protocolVersion    Response HTTP protocol version
        **/
        public function __construct(
            $method             = 'GET',
            $uri                = '/',
            $headers            = array(),
            $body               = null,
            $protocolVersion    = '1.1'
        ) {

            if(!($body instanceof Stream)) {

                $stream = (is_resource($body) ? $body: fopen('php://temp', 'w+'));

                $stream = new Stream($stream);
                $stream->write($body);

                $body = $stream;

            }

            $headers = (is_array($headers) ? new Headers($headers) : $headers);

            parent::__construct($body, $headers, $protocolVersion);

            $this->method   = $method;
            $this->uri      = (is_string($uri) ? Uri::createFromString($uri) : $uri);

        }


        /**
         * Get the request method
         * @return  string      Returns the method name
        **/
        public function getMethod() {

            return $this->method;

        }

        /**
         * Override the method value
         * @param   string      $name       Method name
        **/
        public function setMethod($name) {

            $this->method = $name;

        }


        /**
         * Override the method value, within a request object copy
         * @param       string      $name       Method name
         * @return      Request     Returns a Request object
        **/
        public function withMethod($name) {

            $request = clone $this;
            $request->setMethod($name);

            return $request;

        }


        /**
         * Get the request URI
         * @return      Uri         Returns an instance of \WOK\Uri\Uri
        **/
        public function getUri() {

            return $this->Uri;

        }


        /**
         * Redefine the request URI
         * @param Uri       $uri       Request new URI
        **/
        public function setUri(Uri $uri) {

            $this->uri = $uri;

        }


        /**
         * Redefine the request URI within a request instance copy
         * @param   Uri         $uri       Request new URI
         * @return  Request     Returns a request object containing the new URI
        **/
        public function withUri(Uri $uri) {

            $request = clone $this;
            $request->setUri($uri);

            return $request;


        }


        /**
         * Get the request URI path
         * @return   string     Return the request target Uri Path
        **/
        public function getRequestTarget() {

            $path = (string) $this->uri->getPath();
            return (empty($string) ? '/' : $path);

        }


        /**
         * Redefine the request URI path
         * @param   string     $path        New request URI path
        **/
        public function setRequestTarget($path) {

            $this->uri->setPath($path);

        }


        /**
         * Redefine the request URI path, within a Request object copy
         * @param   string     $path        New request URI path
         * @return  Request    Return a request object copy
        **/
        public function withRequestTarget($path) {

            $request = clone $this;
            $request->setRequestTarget($path);

            return $request;

        }


        /**
         * Request object clone behavior
        **/
        public function __clone() {

            parent::__clone();
            $this->attributes  = clone $attributes;

        }


    }
