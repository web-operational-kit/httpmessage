<?php

    /**
     *
     * @test
     *
     * abstract Message class
     * ===
     *
    **/
    use PHPUnit\Framework\TestCase;

    use \WOK\HttpMessage\Message;
    use \WOK\HttpMessage\Components\Headers;
    use \WOK\HttpMessage\Components\Cookie;
    use \WOK\HttpMessage\Components\CookiesCollection;
    use \WOK\Stream\Stream;

    // Since Message is an abastracted class,
    // a callable class must be defined
    class Msg extends Message {}


    class MessageTest extends TestCase {


        /**
         * Get an instanciante message object
         * ---
        **/
        public function _getMsgInstance($body = '', array $headers = array(), $protocol = '1.1') {

            $headers  = new Headers($headers);

            $resource = fopen('php://temp', 'w+');
            if(!empty($body)) {
                fwrite($resource, $body);
            }

            $body     = new Stream($resource);

            return new Msg($body, $headers, $protocol);

        }


        /**
         * Test Message protocol manipulation methods
         * ---
        **/
        public function testProtocolMethods() {

            $msg = $this->_getMsgInstance('', [], '1.1');

            $this->assertEquals('1.1', $msg->getProtocolVersion(), 'Message::getProtocolVersion must return the protocol version');

            $msg->setProtocolVersion($altVersion = '1.0');
            $this->assertEquals($altVersion, $msg->getProtocolVersion(), 'Message::setProtocolVersion must redefine the protocol version');

            $clone = $msg->withProtocolVersion($cloneVersion = '2.0');
            $this->assertInstanceOf(Message::class, $clone, 'Message::withProtocolVersion must return a message object');
            $this->assertEquals($cloneVersion, $clone->getProtocolVersion(), 'Message::withProtocolVersion must redefine the protocol version');

        }


        /**
         * Test Message headers manipulation methods
         * ---
        **/
        public function testHeadersMethods() {

            $headers             = ['Content-Type' => 'text/plain'];
            $headersCollection   = new Headers($headers);

            $msg = $this->_getMsgInstance('', $headers, '1.1');

            $this->assertInstanceOf(Headers::class, $msg->getHeaders(), 'Message::getHeaders must return an instance of the Headers component');
            $this->assertEquals($headersCollection, $msg->getHeaders(), 'Message::getHeaders must return the headers collection');

            $altCollection = $headersCollection->withHeader('headerName', 'headerValue');
            $msg->setHeaders($altCollection);
            $this->assertEquals($altCollection, $msg->getHeaders(), 'Message::setHeaders must redefine the headers collection');

            $cloneCollection = $headersCollection->withHeader('cloneHeaderName', 'cloneHeaderValue');
            $cloneMessage = $msg->withHeaders($cloneCollection);
            $this->assertInstanceOf(Message::class, $cloneMessage, 'Message::withHeaders must return a message object instance');
            $this->assertEquals($cloneCollection, $cloneMessage->getHeaders(), 'Message::withHeaders must redefine the headers collection');

        }


        /**
         * Test Message cookies manipulation methods
         * ---
        **/
        public function testCookiesMethods() {

            $cookies             = ['cookieName' => new Cookie('cookieName', 'cookieValue')];
            $cookiesCollection   = new CookiesCollection($cookies);

            $msg = $this->_getMsgInstance('', $cookies, '1.1');
            $msg->setCookies($cookiesCollection);

            $this->assertInstanceOf(CookiesCollection::class, $msg->getCookies(), 'Message::getCookies must return an instance of the Headers component');
            $this->assertEquals($cookiesCollection, $msg->getCookies(), 'Message::getCookies must return the cookies collection');

            $altCollection = $cookiesCollection->withCookie(new Cookie('altCookieName'));
            $msg->setCookies($altCollection);
            $this->assertEquals($altCollection, $msg->getCookies(), 'Message::setCookies must redefine the cookies collection');

            $cloneCollection = $cookiesCollection->withCookie(new Cookie('cloneCookieName'));
            $cloneMessage = $msg->withCookies($cloneCollection);
            $this->assertInstanceOf(Message::class, $cloneMessage, 'Message::withCookies must return a message object instance');
            $this->assertEquals($cloneCollection, $cloneMessage->getCookies(), 'Message::withCookies must redefine the cookies collection');

        }


        /**
         * Test Message body manipulation methods
         * ---
        **/
        public function testBodyMethods() {

            $msg = $this->_getMsgInstance($bodyText = 'testBody', [], '1.1');

            $this->assertInstanceOf(Stream::class, $body = $msg->getBody(), 'Message::getBody must return a Stream object instance');
            $this->assertEquals($bodyText, $msg->getBody()->getContent(), 'Message::getBody must return the right resource');

            $altBody = clone $body;
            $altBody->write($altBodyText = 'altTestBody');

            $msg->setBody($altBody);
            $this->assertEquals($altBody, $msg->getBody(), 'Message::getBody must return the right resource');


            $cloneBody = clone $body;
            $cloneBody->write($cloneBodyText = 'cloneTestBody');

            $cloneMessage = $msg->withBody($cloneBody);
            $this->assertInstanceOf(Message::class, $cloneMessage, 'Message::withBody must return a Message object instance');
            $this->assertEquals($cloneBody, $cloneMessage->getBody(), 'Message::getBody must redefine the body instance');

        }


    }
