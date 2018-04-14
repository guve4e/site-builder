<?php

require_once("../../relative-paths.php");
require_once("../UtilityTest.php");
require_once(LIBRARY_PATH . "/configuration/SiteConfiguration.php");

use PHPUnit\Framework\TestCase;

class TestSiteConfiguration extends TestCase
{
    use UtilityTest;

    protected $mockJsonLoader;

    protected $siteConfigurationJson;

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
            "services" => [
                "url_domain" => "http://localhost",
                "url_base_remote" => "http://webapi.ddns.net/index.php",
                "url_base_local" => "http://localhost/site-builder/mock-services"
            ],
            "production" => false,
            "bower_url"  => 'vendor'
        ];

        $json = json_encode($jsonArray);

        $this->siteConfigurationJson = json_decode($json);

        // Create a stub for the JsonLoader class
        $this->mockJsonLoader = $this->getMockBuilder(JsonLoader::class)
            ->setMethods(['getDataAsJson'])
            ->getMock();

        $this->mockJsonLoader->method('getDataAsJson')
            ->willReturn($this->siteConfigurationJson);
    }

    public function testLoadSiteConfiguration()
    {
        try {
            $actualConfiguration = SiteConfiguration::LoadSiteConfiguration($this->mockJsonLoader);
        } catch (Exception $e) {
            echo $e->getMessage();
        }

        // Assert
        $this->assertEquals($this->siteConfigurationJson, $actualConfiguration);
    }
}
