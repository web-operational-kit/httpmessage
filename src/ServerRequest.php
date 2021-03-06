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
    use \WOK\Uri\Components\User;
    use \WOK\Uri\Components\Host;
    use \WOK\Uri\Components\Path;
    use \WOK\Uri\Components\Query;
    use \WOK\Stream\Stream;
    use \WOK\Collection\Collection;

    use WOK\HttpMessage\Components\Headers;
    use WOK\HttpMessage\Components\Attributes;
    use WOK\HttpMessage\Components\FilesCollection;

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
            $server       = array()
        ) {

            parent::__construct($method, $uri, $headers, $body, $protocolVersion);

            $this->files        = (is_array($files)   ? new FilesCollection($files) : $files);
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
            $scheme = (!empty($_SERVER['REQUEST_SCHEME']) ? $_SERVER['REQUEST_SCHEME'] : null);
            if((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || $_SERVER['SERVER_PORT'] == 443) {
                $scheme = 'https';
            }

            $username = (!empty($_SERVER['PHP_AUTH_USER']) ? $_SERVER['PHP_AUTH_USER'] : '');
            $password = (!empty($_SERVER['PHP_AUTH_PW']) ? $_SERVER['PHP_AUTH_PW'] : '');
            $host     = (!empty($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : '');
            $port     = (!empty($_SERVER['SERVER_PORT']) ? $_SERVER['SERVER_PORT'] : ''/*80*/);

            $path     = parse_url($_SERVER['REQUEST_URI']);

            $uri = new Uri(
                $scheme,
                new User($username, $password),
                new Host($host),
                $port,
                new Path(!empty($path['path']) ? $path['path'] : '/'),
                (!empty($path['query']) ? Query::createFromString($path['query']) : new Query(array())),
                (!empty($path['fragment']) ? $path['fragment'] : '')
            );

            // Instanciate ServerRequest
            $request = new self(
                $_SERVER['REQUEST_METHOD'],     // Method
                $uri,                           // Uri
                new Headers(getallheaders()),   // Headers
                new Stream(fopen('php://input', 'w+')),     // Body
                $protocolVersion,               // Protocol version
                new FilesCollection($_FILES),   // Files
                new Collection($_SERVER)        // Server informations
            );

            // Fix PHP input body for submited form
            $postTypes =  ['application/x-www-form-urlencoded', 'multipart/form-data'];
            if($request->getMethod() == 'POST' && in_array($request->getBodyType(), $postTypes)) {

                $stream = new Stream(fopen('php://temp', 'w+'));
                $stream->write(json_encode($_POST));
                $stream->rewind();

                $request->setBody($stream);

            }

            return $request;

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
         * Retrieve server meta data
         * @return  array   Returns the server parameters collections
        **/
        public function getServerParams() {

            return $this->server->all();

        }


        /**
         * Define server parameters
         * @param   array   $parameters     Server parameters collection
        **/
        public function setServerParams(array $parameters) {

            $this->server = new Collection($parameters);

        }


        /**
         * Define server parameters within a ServerRequest copy
         * @param   array               $parameters     Server parameters collection
         * @return  ServerRequest       Returns a ServerRequest copy
        **/
        public function withServerParams(array $parameters) {

            $request = clone $this;
            $request->setServerParams($parameters);

            return $request;

        }


        /**
         * Retrieve a server meta data
         * @param   string                      $name        Server parameter key
         * @throws  InvalidArgumentException    When the parameter doesn't exists
         * @return  mixed                       Returns the server parameter
        **/
        public function getServerParam($name) {

            if(!$this->server->has($name))
                throw new \InvalidArgumentException('The `'.$name.'` server parameter is not defined');

            return $this->server->get($name);

        }


        /**
         * Check if a server parameter is defined
         * @param   string                      $name        Server parameter key
         * @return  boolean                     Returns whether the server parameter exists or not
        **/
        public function hasServerParam($name) {

            return $this->server->has($name);

        }


        /**
         * Define a server parameter
         * @param   string                      $name         Server parameter key
         * @param   mixed                       $value        Server parameter value
        **/
        public function setServerParam($name, $value) {

            $this->server->set($name, $value);

        }


        /**
         * Define a server parameter within a ServerRequest copy
         * @param   string                      $name         Server parameter key
         * @param   mixed                       $value        Server parameter key
         * @return  boolean                     Returns the ServerRequest copy
        **/
        public function withServerParam($name, $value) {

            $request = clone $this;
            $request->setServerParam($name, $value);

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
            $request->setAttributes($attributes);

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
        public function getAttribute($name, $default = false) {

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


        /**
         * Retrieve request media type
         * @return string   Returns the media type (from MIME Content-Type)
        **/
        public function getBodyType() {

           $ctype = $this->getHeader('Content-Type', null);
           $parts = preg_split('/\s*[;,]\s*/', $ctype);
           return mb_strtolower($parts[0]);

       }


       /**
         * Get the request body parameters
         * @return array Returns the request body parameters
        **/
        public function getBodyMeta() {

            $ctype = $this->getHeader('Content-Type', null);

            $parts      = preg_split('/\s*[;,]\s*/', $ctype);
            $length     = count($parts);

            $parameters = array();
            for ($i = 1; $i < $length; $i++) {
                list($name, $value) = explode('=', $parts[$i], 2);
                $parameters[mb_strtolower($name)] = $value;
            }

            return $parameters;

        }


       /**
         * Get the request body charset
         * @param   string      $default        Supposed charset value
         * @return  string
        **/
        public function getBodyCharset($default = null) {

            if(empty($default)) {
                $default = mb_internal_encoding();
            }

            $parameters = $this->getBodyMeta();
            return (isset($parameters['charset']) ? $parameters['charset'] : $default);

        }


       /**
         * Get the parsed request body
         * @return mixed   Return the parsed request body
        **/
        public function getParsedBody() {

            $type       = $this->getBodyType();
            $body       = $this->body->getContent();
            $charset    = mb_strtoupper($this->getBodyCharset());

            // Get the right body charset
            if($charset != mb_strtoupper(mb_internal_encoding())) {
                $body = mb_convert_encoding($body, $charset, mb_internal_encoding());
            }

            // JSON
            if($type == 'application/json') {
                return json_decode($body, true);
            }

            // XML
            elseif($type == 'application/xml' || $type == 'text/xml') {

                $backup = libxml_disable_entity_loader(true);
                $xml = simplexml_load_string($body);
                libxml_disable_entity_loader($backup);
                return $xml;

            }

            // POST
            elseif(in_array($type, ['application/x-www-form-urlencoded', 'multipart/form-data'])) {
                return json_decode($body, true);
            }

            // Not parsed body
            else {
                return $body;
            }

        }


        /**
         * Retrieve the uploaded files collection
         * @return  FilesCollection     Return a FilesCollection object
        **/
        public function getUploadedFiles() {

            return $this->files;

        }


        /**
         * Retrieve the client IP address
         * @return string       Returns the client IP address
        **/
        public function getClientIp() {

            $server  = $this->server->all();
            $methods = array(
                'HTTP_CLIENT_IP',
                'HTTP_X_FORWARDED_FOR',
                'HTTP_X_FORWARDED',
                'HTTP_X_CLUSTER_CLIENT_IP',
                'HTTP_FORWARDED_FOR',
                'HTTP_FORWARDED',
                'REMOTE_ADDR'
            );

            foreach($methods as $key) {

                if(array_key_exists($key, $server) === true) {
                    foreach (explode(',', $server[$key]) as $ip) {

                        $ip = trim($ip); // trim for safety measures

                        // Validate IP
                        if( filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4 | FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) ) {
                            return $ip;
                        }

                    }
                }

            }

            return isset($server['REMOTE_ADDR']) ? $server['REMOTE_ADDR'] : false;

        }


        /**
         * Request object clone behavior
        **/
        public function __clone() {

            parent::__clone();

            $this->files        = clone $this->files;
            $this->headers      = clone $this->headers;
            $this->attributes   = clone $this->attributes;

        }


    }
