<?php

use PHPUnit\Framework\TestCase;
require_once("../../../relative-paths.php");
require_once (HTTP_PATH . "/phphttp/RestCall.php");
require_once(UTILITY_PATH . "/FileManager.php");

class RestResponseTest extends TestCase
{
    public function testRestResponseClassNotSuccessful()
    {
        $restResponse = new RestResponse();
        try {
            $restResponse->setBody("Some Body")
                ->setHttpCode(500)
                ->setTime(124835, 124838);
        } catch (Exception $e) {
            echo $e->getMessage();
        }

        $this->assertEquals("Some Body", $restResponse->getBodyRaw());
        $this->assertEquals(["time" => 3.0, "code"=>500, "success"=>false], $restResponse->getInfo());
        $this->assertEquals(false, $restResponse->isSuccessful());
    }

    public function testRestResponseClassSuccessful()
    {
        $restResponse = new RestResponse();
        try {
            $restResponse->setBody("Some Body")
                ->setHttpCode(200)
                ->setTime(124835, 124845);
        } catch (Exception $e) {
            echo $e->getMessage();
        }

        $this->assertEquals("Some Body", $restResponse->getBodyRaw());
        $this->assertEquals(["time" => 10.0, "code"=>200, "success"=>true], $restResponse->getInfo());
        $this->assertEquals(true, $restResponse->isSuccessful());
    }

    public function testRestResponseGetBodyAsJson()
    {
        $expectedJson = json_decode("{ \"key\": \"value\" }");

        $restResponse = new RestResponse();
        try {
            $restResponse->setBody("{ \"key\": \"value\" }")
                ->setHttpCode(200)
                ->setTime(124835, 124845);
        } catch (Exception $e) {
            echo $e->getMessage();
        }

        $this->assertEquals($expectedJson, $restResponse->getBodyAsJson());
    }

    public function testRestResponseGetBodyAsArray()
    {
        $restResponse = new RestResponse();
        try {
            $restResponse->setBody("{ \"key\": \"value\" }")
                ->setHttpCode(200)
                ->setTime(124835, 124845);
        } catch (Exception $e) {
            echo $e->getMessage();
        }

        $this->assertEquals(["key" => "value"], $restResponse->getBodyAsArray());
    }
}


