<?php

use PHPUnit\Framework\TestCase;
require_once ("../../config.php");
require_once (HTTP_PATH . "/phphttp/RestCall.php");
require_once (LIBRARY_PATH . "/http/PhpHttpAdapter.php");
require_once (UTILITY_PATH . "/File.php");

class PhpAdapterTest extends TestCase
{
    private $mockConnection;

    protected function setUp()
    {
        $httpResponse = "HTTP/1.1 200 OK" . "\r\n" .
                        "Date: Mon, 27 Jul 2009 12:28:53 GMT" . "\r\n" .
                        "Server: Apache/2.2.14 (Win32)" . "\r\n" .
                        "Last-Modified: Wed, 22 Jul 2009 19:15:56 GMT" . "\r\n" .
                        "Content-Length: 88" . "\r\n" .
                        "Content-Type: text/html" . "\r\n" .
                        "Connection: Closed" . "\r\n\r\n" .
                        "{ 'key' => 'value', 'title' => 'some_title' }";

        // Create a stub for the JsonLoader class
        $this->mockFile = $this->getMockBuilder(RestCall::class)
            ->setMethods(array('send', 'jsonDecode', 'loadFileContent'))
            ->getMock();

        $this->mockFile->method('getResponseAsJson')
            ->willReturn(["key" => "Value"]);


        // Create a stub for the JsonLoader class
        $this->mockConnection = $this->getMockBuilder(PhpAdapterTest::class)
            ->setMethods(array('constructRestCall'))
            ->getMock();

        $this->mockConnection->method('constructRestCall')
            ->willReturn($this->mockFile);
    }

    public function testSocketCall()
    {
        // Arrange
        $expectedString = json_encode("{ 'key' => 'value', 'title' => 'some_title' }");

        try {

            // get info from web-api
            $rc = new PhpHttpAdapter();
            $rc->setServiceName('Products')
                ->setMethod('GET');

            $productsRaw = $rc->send();

        } catch (Exception $e) {
            echo $e->getMessage();
        }

//        $this->assertEquals($expectedString, $responseAsJson);
//        $this->assertEquals("{ 'key' => 'value', 'title' => 'some_title' }", $responseAsString);
    }
}


