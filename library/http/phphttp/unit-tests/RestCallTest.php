<?php

use PHPUnit\Framework\TestCase;
require_once ("../../../../config.php");
require_once ("../phphttp/RestCall.php");
require_once ("UtilityTest.php");
require_once (UTILITY_PATH . "/File.php");

class RestCallTest extends TestCase
{
    use UtilityTest;

    private $mockConnection;

    /**
     * Create test subject before test
     */
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
        $this->mockConnection = $this->getMockBuilder(File::class)
            ->setMethods(array('fileExists', 'close', 'getLine', 'endOfFile', 'socket', 'write'))
            ->getMock();

        $this->mockConnection->method('fileExists')
            ->willReturn(true);

        $this->mockConnection->method('getLine')
            ->will($this->onConsecutiveCalls($httpResponse, false)); // break the loop

        $this->mockConnection->expects($this->at(2))
            ->method('endOfFile')
            ->with(null)
            ->willReturn(false);

        $this->mockConnection->expects($this->at(4))
            ->method('endOfFile')
            ->with(null)
            ->willReturn(true);
    }

    public function testSocketCall()
    {
        // Arrange
        $expectedString = json_encode("{ 'key' => 'value', 'title' => 'some_title' }");

        try {
            $restCall = new RestCall("Socket", $this->mockConnection);
            $restCall->setUrl("http://webapi.ddns.net/index.php/mockcontroller/1001");
            $restCall->setContentType("application/json");
            $restCall->setMethod("POST");
            $restCall->addBody(["a" => 'b']);
            $restCall->send();
            $responseAsJson = $restCall->getResponseAsJson();
            $responseAsString = $restCall->getResponseAsString();
        } catch (Exception $e) {
            echo $e->getMessage();
        }

        $this->assertEquals($expectedString, $responseAsJson);
        $this->assertEquals("{ 'key' => 'value', 'title' => 'some_title' }", $responseAsString);
    }
}


