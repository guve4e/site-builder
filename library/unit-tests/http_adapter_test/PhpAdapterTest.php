<?php

use PHPUnit\Framework\TestCase;
require_once ("../../../relative-paths.php");
require_once (HTTP_PATH . "/phphttp/RestCall.php");
require_once (LIBRARY_PATH . "/http/PhpHttpAdapter.php");
require_once (CONFIGURATION_PATH . "/SiteConfigurationLoader.php");
require_once(UTILITY_PATH . "/FileManager.php");

class PhpAdapterTest extends TestCase
{
    private $restCall;

    private $siteConfigurationJson;

    private $jsonString;

    protected function setUp()
    {
        $jsonArray = [
            "debug" => true,
            "session" => [
                "key" => "some_website_user",
                "time" => 0
            ],
            "cookie" => [
                "name" => "some_website_cookie",
                "time" => 7
            ],
            "web_services" => [
                [
                    "name" => "webapi",
                    "url_base_remote" => "http://webapi.ddns.net/index.php",
                    "url_base_local" => "http://localhost/site-builder/mock-form",
                    "api_token" => "WRCdmach38E2*$%Ghdo@nf#cOBD4fd"
                ],
                [
                    "name" => "some_webapi",
                    "url_base_remote" => "http://some_webapi.ddns.net/index.php",
                    "url_base_local" => "http://localhost/some-mock-form",
                    "api_token" => "JININCN868767GUG&%#@*&%@#GUIG#"
                ]
            ],
            "production" => false,
            "bower_url"  => 'vendor'
        ];


        $json = json_encode($jsonArray);
        $this->siteConfigurationJson = json_decode($json);

        $this->restCall = $this->getMockBuilder(RestCall::class)
            ->setConstructorArgs(["Curl", new FileManager])
            ->setMethods(['send', 'getResponseWithInfo'])
            ->getMock();

        try {

            $this->jsonString = "{ \"data\": { \"controller\": \"Test\", \"method\": \"GET\", \"id\": \"1001\" }, \"key\": \"value\" }";

            $restResponse = new RestResponse();
            $restResponse->setBody($this->jsonString)
                ->setHttpCode(200)
                ->setTime(124835, 124838);
        } catch (Exception $e) {
            echo $e->getMessage();
        }

        $this->restCall->method('getResponseWithInfo')
            ->willReturn($restResponse);
    }

    public function testPhpHttpAdapterCall()
    {
        // Arrange
        $expectedString = json_decode($this->jsonString);

        try {
            // Act
            $rc = new PhpHttpAdapter();
            $rc->setRestCallType($this->restCall)
                ->setWebServicesConfiguration($this->siteConfigurationJson)
                ->setWebServiceName("some_webapi")
                ->setController('Products')
                ->setMethod('GET');

            $response = $rc->send();

        } catch (Exception $e) {
            echo $e->getMessage();
        }
        // Assert
        $this->assertEquals($expectedString, $response);
    }
}


