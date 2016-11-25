<?php


    use PHPUnit\Framework\TestCase;

    use \WOK\HttpMessage\Response;
    use \WOK\HttpMessage\Components\Headers;
    use \WOK\Stream\Stream;

    class ResponseTest extends TestCase {


        public function __construct() {

            $this->response = new Response;

        }

        /**
         * Test headers integration
         * ---
        **/
        public function testHeadersIntegration() {

            $headers = $this->response->getHeaders();
            $this->assertInstanceOf(Headers::class, $headers, 'Response::getHeaders() must return a Headers instance');

            $headers->setHeader('Content-Type', 'text/plain');
            $this->response->setHeaders($headers);
            $this->assertEquals($headers, $headers, 'Response::setHeaders() must redefine Headers instance');

        }


        /**
         * Test body integration
         * ---
        **/
        public function testBodyIntegration() {

            $body = $this->response->getBody();
            $this->assertInstanceOf(Stream::class, $body, 'Response::getBody() must return a Stream instance');


            $body->write('abcd');
            $this->response->setBody($body);
            $this->assertEquals($body, $this->response->getbody(), 'Response::setBody() must redefine Stream instance');

            $this->assertInstanceOf(Response::class, $this->response->withBody($body), 'Response::withBody() must return a response instance');

        }





    }
