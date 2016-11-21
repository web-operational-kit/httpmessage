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

    use Psr\Http\Message\MessageInterface;
    use Psr\Http\Message\StreamInterface;
    use WOK\Stream\Stream;
    use Components\Headers;


    /**
     * The Message abstracted class provide
     * common methods for both HTTP request and response.
    **/
    abstract class Message implements MessageInterface {

        /**
         * @var     string     $protocolVersion     HTTP Protocol version
        **/
        protected $protocolVersion = '1.1';

        /**
         * @var     Headers     $headers            Headers message collection
        **/
        protected $headers;

        /**
         * @var     Stream      $body               Body stream handler
        **/
        protected $body;


        /**
         * Instanciate Message object
         * @param Stream     $body       Body stream handler
         * @param Headers    $body       Body stream handler
        **/
        public function __construct(StreamInterface $body, Headers $headers) {

            $this->body     = $this->setBody($body);
            $this->headers  = $this->setHeaders($headers);

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
        protected function getHeaders() {
            return $this->headers;
        }


        /**
         * Override headers list
         * @param array|Headers     $headers        Headers new list
        **/
        protected function setHeaders($headers) {
            $this->headers = (is_array($headers) ? new Headers($headers) = $headers);
        }


        /**
         * Override headers list in a new instance
         * @param array|Headers     $headers        Headers new list
        **/
        protected function withHeaders($headers) {

            $message = clone $this;
            $message->setHeaders($headers);

            return $message;

        }


        /**
         * Retrieve message body
         * @return Body     Return body instance
        **/
        protected function getBody() {
            return $this->body;
        }


        /**
         * Redefine message body
         * @param   Stream      Message new body
        **/
        protected function setBody(StreamInterface $body) {
            $this->body = $body;
        }


        /**
         * Redefine message body within a new instance
         * @param   Stream      Message new body
         * @return  Body        Returns a message instance copy with the new body
        **/
        protected function withBody(StreamInterface $body) {

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
            $this->body     = clone $this->body;

        }


    }
