<?php

use PHPUnit\Framework\TestCase;
require_once ("../../config.php");
require_once (HTTP_PATH . "/phphttp/RestCall.php");
require_once (LIBRARY_PATH . "/http/PhpHttpAdapter.php");
require_once (UTILITY_PATH . "/File.php");

class PhpAdapterTest extends TestCase
{
    private $mockConnection;

    private $restCall;

    protected function setUp()
    {
        // Create a stub for the JsonLoader class
        $this->restCall = $this->getMockBuilder(RestCall::class)
            ->setConstructorArgs(["Curl", new File])
            ->setMethods(['send', 'getResponseWithInfo'])
            ->getMock();

        try {
            $restResponse = new RestResponse();
            $restResponse->setBody("{ 'key' => 'value', 'title' => 'some_title' }")
                ->setHttpCode(200)
                ->setTime(124835, 124838);
        } catch (Exception $e) {
            echo $e->getMessage();
        }

        $this->restCall->method('getResponseWithInfo')
            ->willReturn($restResponse);
    }

    public function tesPhpHttpAdapterCall()
    {
        // Arrange
        $expectedString = json_encode("{ 'key' => 'value', 'title' => 'some_title' }");

        try {

            // get info from web-api
            $rc = new PhpHttpAdapter($this->restCall);
            $rc->setServiceName('Products')
                ->setMethod('GET');

            $response = $rc->send();


        } catch (Exception $e) {
            echo $e->getMessage();
        }

        $this->assertEquals($expectedString, $response);
    }
}


