<?php


    use PHPUnit\Framework\TestCase;

    use \WOK\HttpMessage\ServerRequest;
    use \WOK\HttpMessage\Components\Headers;
    use \WOK\Stream\Stream;

    class ServerRequestTest extends TestCase {

        /**
         * Test global request
         * ---
        **/
        public function testGlobalsInstanciation() {

            $GLOBALS['_SERVER'] = array(
                'SERVER_PROTOCOL'     => 'HTTP/1.1',
                'HTTP_HOST'           => 'test.dev',
                'REQUEST_URI'         => '/request/uri/path?param1=val&param2',
                'REQUEST_SCHEME'      => '',
                'HTTPS'               => 'on',
                'PHP_AUTH_USER'       => null,
                'PHP_AUTH_PW'         => null,
                'SERVER_PORT'         => 80,
                'REQUEST_METHOD'      => 'POST'
            );

            $request = ServerRequest::createFromGlobals();

            $this->assertInstanceOf(ServerRequest::class, $request);


        }


    }

    /**
     * getallheaders() polyfill
     * ---
    **/
    if (!function_exists('getallheaders')) {
        function getallheaders() {
            $headers = '';
            foreach ($_SERVER as $name => $value) {
                if (substr($name, 0, 5) == 'HTTP_') {
                    $headers[str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($name, 5)))))] = $value;
                }
            }
           return $headers;
        }
    }
