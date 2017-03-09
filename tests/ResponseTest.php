<?php

    use PHPUnit\Framework\TestCase;

    use \WOK\HttpMessage\Request;
    use \WOK\HttpMessage\Response;

    class ResponseTest extends TestCase {


        /**
         * Instanciante response from a request object
         * ---
        **/
        public function testInstanciationFromRequest() {

            $request     = new Request();
            $response    = Response::createFromRequest($request);

            $this->assertInstanceOf(Response::class, $response, 'Response::createFromRequest must return a Response object');


        }


    }
