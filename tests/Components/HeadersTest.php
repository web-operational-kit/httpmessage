<?php

    use PHPUnit\Framework\TestCase;

    use \WOK\HttpMessage\Components\Headers;

    class HeadersTest extends TestCase {


        static protected $initialHeaders = array(
            'Multiple-Value'            => 'gzip, deflate, sdch, br',
            'InSensiTiveCase-HeaDer'    => 'insensitive'
        );


        /**
         * Instanciate default header component
         * ---
        **/
        public function __construct() {

            $this->headers = new Headers(self::$initialHeaders);

        }


        /**
         * Headers object copy is supposed to be different from the inital one
         * ---
        **/
        public function testImmutability() {

            $initalCopy = clone $this->headers;
            $headers    = $this->headers->withHeader('new-header', 'value');

            $this->assertEquals($initalCopy, $this->headers);
            $this->assertNotEquals($this->headers, $headers);

        }


        /**
         * Headers manipulation is supposed to be case insensitive
         * ---
        **/
        public function testInsensitivity() {

            $headerValue = $this->headers->getHeader('insensitivecase-header');
            $this->assertEquals(self::$initialHeaders['InSensiTiveCase-HeaDer'], $headerValue);

        }


    }
