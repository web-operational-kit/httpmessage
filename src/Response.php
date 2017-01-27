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

    /**
     * The Message abstracted class provide
     * common methods for both HTTP request and response.
    **/
    class Response extends Message {

        /**
         * @var     integer     $statusCode         Response status code
        **/
        protected $statusCode = 200;

        /**
         * @var     integer     $reasonPhrase       Response reason phrase
        **/
        protected $reasonPhrase = null;


        /**
         * @var     array       $messages           Accepted reason phrases
        **/
        protected static $messages = array(
            //Informational 1xx
           100 => 'Continue',
           101 => 'Switching Protocols',
           102 => 'Processing',
           //Successful 2xx
           200 => 'OK',
           201 => 'Created',
           202 => 'Accepted',
           203 => 'Non-Authoritative Information',
           204 => 'No Content',
           205 => 'Reset Content',
           206 => 'Partial Content',
           207 => 'Multi-Status',
           208 => 'Already Reported',
           226 => 'IM Used',
           //Redirection 3xx
           300 => 'Multiple Choices',
           301 => 'Moved Permanently',
           302 => 'Found',
           303 => 'See Other',
           304 => 'Not Modified',
           305 => 'Use Proxy',
           306 => '(Unused)',
           307 => 'Temporary Redirect',
           308 => 'Permanent Redirect',
           //Client Error 4xx
           400 => 'Bad Request',
           401 => 'Unauthorized',
           402 => 'Payment Required',
           403 => 'Forbidden',
           404 => 'Not Found',
           405 => 'Method Not Allowed',
           406 => 'Not Acceptable',
           407 => 'Proxy Authentication Required',
           408 => 'Request Timeout',
           409 => 'Conflict',
           410 => 'Gone',
           411 => 'Length Required',
           412 => 'Precondition Failed',
           413 => 'Request Entity Too Large',
           414 => 'Request-URI Too Long',
           415 => 'Unsupported Media Type',
           416 => 'Requested Range Not Satisfiable',
           417 => 'Expectation Failed',
           418 => 'I\'m a teapot',
           421 => 'Misdirected Request',
           422 => 'Unprocessable Entity',
           423 => 'Locked',
           424 => 'Failed Dependency',
           426 => 'Upgrade Required',
           428 => 'Precondition Required',
           429 => 'Too Many Requests',
           431 => 'Request Header Fields Too Large',
           444 => 'Connection Closed Without Response',
           451 => 'Unavailable For Legal Reasons',
           499 => 'Client Closed Request',
           //Server Error 5xx
           500 => 'Internal Server Error',
           501 => 'Not Implemented',
           502 => 'Bad Gateway',
           503 => 'Service Unavailable',
           504 => 'Gateway Timeout',
           505 => 'HTTP Version Not Supported',
           506 => 'Variant Also Negotiates',
           507 => 'Insufficient Storage',
           508 => 'Loop Detected',
           510 => 'Not Extended',
           511 => 'Network Authentication Required',
           599 => 'Network Connect Timeout Error',
       );


        /**
         * Instanciate a new Response object
         * @param   integer             $statusCode         Response HTTP code
         * @param   string              $reasonPhrase       Response HTTP code associated notice
         * @param   array               $headers            Response headers
         * @param   string|Stream       $body               Response body
         * @param   string              $protocolVersion    Response HTTP protocol version
        **/
        public function __construct(
            $statusCode = 200,
            $reasonPhrase = null,
            array $headers = array(),
            $body = null,
            $protocolVersion = '1.1'
        ) {

            if(!($body instanceof Stream)) {

                $stream = (is_resource($body) ? $body: fopen('php://temp', 'w+'));

                $stream = new Stream($stream);

                if(!is_resource($body)) {
                    $stream->write($body);
                }

                $body = $stream;

            }

            $headers = (is_array($headers) ? new Headers($headers) : $headers);

            parent::__construct($body, $headers, $protocolVersion);

            $this->statusCode = $statusCode;
            $this->reasonPhrase = $reasonPhrase;

        }

        /**
         * Get the status value (as `$statusCode $reasonPhrase`)
         * @return  integer     Returns the status code
        **/
        public function getStatus() {

            return $this->statusCode.' '.$this->reasonPhrase;

        }


        /**
         * Redefine response status
         * @param   integer     $code           Status code
         * @param   string      $reason         Status reason phrase
        **/
        public function setStatus($code, $reason = null) {

            if (!is_integer($code) || $code < 100 || $code > 599) {
                throw new \InvalidArgumentException('Invalid HTTP status code');
            }

            $this->statusCode = $code;
            $this->setReasonPhrase($reason);

        }


        /**
         * Redefine response status within a reponse copy instance
         * @param   integer     $code           Status code
         * @param   string      $reason         Status reason phrase
         * @return      Response    Returns the response copy instance
        **/
        public function withStatus($code, $reason = null) {

            $response = clone $this;
            $response->setStatus($code, $reason);

            return $response;

        }

        /**
         * Retrieve the status code
         * @return     integer     Returns the status code
        **/
        public function getStatusCode() {
            return $this->statusCode;
        }


        /**
         * Define status code
         * @param integer     $code         Status code
        **/
        public function setStatuscode($code) {
            $this->statuscode = (int) $code;
        }


        /**
         * Define status code within a response object copy
         * @param  integer     $code         Status code
         * @return Response    Return the response instance copy with the new status code
        **/
        public function withStatuscode($code) {

            $response = clone $this;
            $response->setStatusCode($code);

            return $response;

        }


        /**
         * Get the response status reason phrase
         * @return string   Return the reason phrase
        **/
        public function getReasonPhrase() {

            return $this->reasonPhrase;

        }


        /**
         * Redefine reason phrase
         * @param   string      $reason         New reason phrase
        **/
        public function setReasonPhrase($reason = null) {

            if(is_null($reason) && isset(static::$messages[$this->statusCode])) {
                $this->reasonPhrase = static::$messages[$this->statusCode];
            }
            else {
                $this->reasonPhrase = $reason;
            }

        }


        /**
         * Redefine reason phrase within a response instance copy
         * @param       string          $reason         New reason phrase
         * @return      Response        Returns the response instance copy with the new reason phrase
        **/
        public function withReasonPhrase($reason = null) {

            $response = clone $this;
            $response->setReasonPhrase($reason);

            return $response;

        }


    }
