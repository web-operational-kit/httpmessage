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
    use \WOK\Collection\Collection;

    use Components\Headers;
    use Components\Attributes;
    use Components\FilesCollection;

    /**
     * The Request class provide an interface
     * to manage an HTTP request
    **/
    class ServerRequest extends Request {

        /**
         * @var Collection      $server     Server collection instance
        **/
        protected $server;

        /**
         * @var Cookies         $cookies     Cookies collection instance
        **/
        protected $cookies;

        /**
         * @var Files           $files        Files collection instance
        **/
        protected $files;


        /**
         * @var Collection     $attributes    Request meta data collection (attributes)
        **/
        protected $attributes;


        /**
         * Instanciate a new ServerRequest object
         * @param   string              $method             Response HTTP code associated notice
         * @param   string|Uri          $uri                Response HTTP code associated notice
         * @param   array               $headers            Response headers
         * @param   string|Stream       $body               Response body
         * @param   string              $protocolVersion    Response HTTP protocol version
         * @param   array|Files         $files              Server Request files collection
         * @param   array|Cookies       $cookies            Server Request cookies collection
         * @param   array|collection    $server             Server Request server data collection
        **/
        public function __construct(
            $method,
            $uri,
            $headers      = array(),
            $body               = null,
            $protocolVersion    = '1.1',
            $files        = array(),
            $cookies      = array(),
            $server       = array()
        ) {

            parent::__construct($method, $uri, $headers, $body, $protocolVersion);

            $this->files        = (is_array($files)   ? new FilesCollection($files) : $files);
            $this->cookies      = (is_array($cookies) ? new Cookies($cookies)       : $cookies);
            $this->server       = (is_array($server)  ? new Collection($server)     : $server);
            $this->attributes   = new Attributes(array());

        }

        /**
         * Generate a new ServerRequest instance from internal server data
         * @return  ServerRequest       Returns a ServerRequest Instance
        **/
        public static function createFromGlobals() {

            // Calculate protocol version
            $protocolVersion = mb_substr(mb_strstr($_SERVER['SERVER_PROTOCOL'], '/'), 1);

            // Instanciate Uri component
            $path = parse_url($_SERVER['REQUEST_URI']);
            $uri = new Uri(
                (!empty($_SERVER['REQUEST_SCHEME']) ? $_SERVER['REQUEST_SCHEME'] : (strtolower($_SERVER['HTTPS']) == 'on' ? 'https' : 'http')),
                (!empty($_SERVER['PHP_AUTH_USER']) ? $_SERVER['PHP_AUTH_USER'] : ''),
                (!empty($_SERVER['PHP_AUTH_PW']) ? $_SERVER['PHP_AUTH_PW'] : ''),
                (!empty($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : ''),
                (!empty($_SERVER['SERVER_PORT']) ? $_SERVER['SERVER_PORT'] : ''/*80*/),
                (!empty($path['path']) ? $path['path'] : '/'),
                (!empty($path['query']) ? $path['query'] : ''),
                (!empty($path['fragment']) ? $path['fragment'] : '')
            );

            // Instanciate ServerRequest
            return self::__construct(
                $_SERVER['REQUEST_METHOD'],     // Method
                $uri,                           // Uri
                new Headers(getallheaders()),   // Headers
                fopen('php://input', 'r+'),     // Body
                $protocolVersion,               // Protocol version
                new FilesCollection($_FILES),             // Files
                new Cookies($_COOKIES),         // Cookies
                new Collection($_SERVER)        // Server informations
            );

        }


        /**
         * Get the uploaded files
         * @return  Files       Return the Files collection object
        **/
        public function getUploadedFiles() {

            return $this->files;

        }


        /**
         * Redefine uploaded files
         * @param array|Files      $files       New files collection
        **/
        public function setUploadedFiles($files) {

            $this->files = (is_array($files) ? new FilesCollection($files) : $files);

        }

        /**
         * Redefine uploaded files within an instance copy
         * @param   array|Files      $files       New files collection
         * @return  ServerRequest    Returns a copy of the ServerRequest object
        **/
        public function withUploadedFiles($files) {

            $request = clone $this;
            $request->setUploadedFiles($files);

            return $request;

        }

        /**
         * Retrieve attributes collection
         * @return Attributes     Returns the attribute collection
        */
        public function getAttributes() {

            return $this->attributes;

        }


        /**
         * Define attributes collection
         * @param      array          $attributes         Array of attributes as [$name => $value]
        */
        public function setAttributes(array $attributes) {

            $this->attributes = new Attributes($attributes);

        }


        /**
         * Define attributes collection within a ServerRequest instance copy
         * @param      array          $attributes         Array of attributes as [$name => $value]
         * @return     ServerRequest                      Returns a ServerRequest copy with the new attributes
        */
        public function withAttributes(array $attributes) {

            $request = clone $this;
            $request->setAttirbutes($attributes);

            return $request;

        }


        /**
         * Check whether an attribute has been defined or not
         * @param     string     $name         Attribute's name
         * @return    bool       Returns true if the attribute exists, false otherwise
        **/
        public function hasAttribute($name) {

            return $this->attributes->hasAttribute($name);

        }


        /**
         * Get an attribute's value
         * @param     string     $name         Attribute's name
         * @param     mixed      $default      Attribute's default value
         * @return    mixed      Returns the attribute value, or $default
        **/
        public function getAttribute($name, $default) {

            return $this->attributes->getAttribute($name, $default);

        }


        /**
         * Define an attribute
         * @param     string     $name         Attribute's name
         * @param     mixed      $value        Attribute's value
        **/
        public function setAttribute($name, $value) {

            $this->attributes->setAttribute($name, $value);

        }

        /**
         * Define an attribute within a ServerRequest instance copy
         * @param     string     $name         Attribute's name
         * @param     mixed      $value        Attribute's value
         * @return    ServerRequest            Returns a ServerRequest instance copy
        **/
        public function withAttribute($name, $value) {

            $request = clone $this;
            $request->setAttribute($name, $value);

            return $request;

        }


        /**
         * Remove a defined attribute
         * @param string     $name         Attribute's name
        **/
        public function removeAttribute($name) {

            $this->attributes->removeAttribute($name);

        }


        /**
         * Remove a defined attribute within a ServerRequest instance copy
         * @param string     $name         Attribute's name
        **/
        public function withoutAttribute($name) {

            $request = clone $this;
            $request->removeAttribute($name, $value);

            return $request;

        }



        /* @todo Implements the following methods
            public function getParsedBody() {}
            public function setParsedBody($data) {}
            public function withParsedBody($data) {}
        */


        /**
         * Request object clone behavior
        **/
        public function __clone() {

            parent::__clone();

            $this->files        = clone $files;
            $this->cookies      = clone $cookies;
            $this->headers      = clone $headers;
            $this->attributes   = clone $attributes;

        }


    }
