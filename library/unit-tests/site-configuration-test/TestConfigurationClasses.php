<?php

require_once ("../../../relative-paths.php");
require_once (UTILITY_PATH . "/JsonLoader.php");
require_once (CONFIGURATION_PATH . "/SiteConfigurationLoader.php");
require_once (CONFIGURATION_PATH . "/TemplateConfigurationLoader.php");
require_once (CONFIGURATION_PATH . "/ViewConfigurationLoader.php");
require_once (CONFIGURATION_PATH . "/MenuConfigurationLoader.php");

use PHPUnit\Framework\TestCase;

class JsonLoaderTest extends TestCase
{
    public function testTemplateConfigurationLoader()
    {
        // Arrange
        $templateJson = [
            "title" => "Site Title",

            "styles" => [
                [
                    "rel" => "stylesheet",
                    "type" => "",
                    "src" => "bower",
                    "path" => "/uikit/css/",
                    "name" => "uikit.almost-flat",
                    "media"=> "all",
                    "min" => true
                ],
                [
                    "rel" => "stylesheet",
                    "type" => "",
                    "src" => "",
                    "path" => "assets/icons/flags/",
                    "name" => "flags",
                    "media" => "all",
                    "min" => true
                ]
            ],
            "scripts" => [
                [
                    "src" => "",
                    "path" => "assets/js/",
                    "name" => "common",
                    "min" => false
                ],
                [
                    "src" => "",
                    "path" => "assets/js/",
                    "name" => "uikit_custom",
                    "min" => true
                ]
            ]
        ];

        // Create a stub for the JsonLoader class
        $mockFile = $this->getMockBuilder(FileManager::class)
            ->setMethods(array('fileExists', 'jsonDecode'))
            ->getMock();

        $mockFile->method('fileExists')
            ->willReturn(true);

        $mockFile->method('jsonDecode')
            ->willReturn($templateJson);

        try {
            // Act
            $jsonLoader = new TemplateConfigurationLoader($mockFile);
            $actualData = $jsonLoader->getData();
        } catch (Exception $e) {
            echo $e->getMessage();
        }

        $this->assertEquals($templateJson, $actualData);
    }

    /**
     * @expectedException Exception
     */
    public function testTemplateConfigurationLoaderMustThrowExceptionWhenWrongConfig()
    {
        // Arrange
        $siteJsonMissingStyles = [
            "title" => "Site Title",

            "scripts" => [
                [
                    "src" => "",
                    "path" => "assets/js/",
                    "name" => "common",
                    "min" => false
                ],
                [
                    "src" => "",
                    "path" => "assets/js/",
                    "name" => "uikit_custom",
                    "min" => true
                ]
            ]
        ];

        // Create a stub for the JsonLoader class
        $mockFile = $this->getMockBuilder(FileManager::class)
            ->setMethods(array('fileExists', 'jsonDecode'))
            ->getMock();

        $mockFile->method('fileExists')
            ->willReturn(true);

        $mockFile->method('jsonDecode')
            ->willReturn($siteJsonMissingStyles);

        // Act
        new TemplateConfigurationLoader($mockFile);
    }

    public function testSiteConfigurationLoader()
    {
        // Arrange
        $siteJson = [
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

        $json = json_encode($siteJson);

        $expectedSiteConfigurationJson = json_decode($json);

        $mockFile = $this->getMockBuilder(FileManager::class)
            ->setMethods(array('fileExists', 'jsonDecode'))
            ->getMock();

        $mockFile->method('fileExists')
            ->willReturn(true);

        $mockFile->method('jsonDecode')
            ->willReturn($siteJson);

        // Act
        $jsonLoader = new SiteConfigurationLoader($mockFile);
        $actualData = $jsonLoader->getData();

        $this->assertEquals($expectedSiteConfigurationJson, $actualData);
    }

    public function testViewConfigurationLoader()
    {
        // Arrange
        $siteJson = [
            "view" => "page_home",
            "title" => "Home",
            "full_screen" => true,
            "body_class_style"=> "",
            "styles" => [],
            "scripts" => []
        ];

        // Create a stub for the JsonLoader class
        $mockFile = $this->getMockBuilder(FileManager::class)
            ->setMethods(array('fileExists', 'jsonDecode'))
            ->getMock();

        $mockFile->method('fileExists')
            ->willReturn(true);

        $mockFile->method('jsonDecode')
            ->willReturn($siteJson);

        try {
            // Act
            $jsonLoader = new ViewConfigurationLoader($mockFile, "home");
            $actualData = $jsonLoader->getData();
        } catch (Exception $e) {
            echo $e->getMessage();
        }

        $this->assertEquals($siteJson, $actualData);
    }

    /**
     * @expectedException  Exception
     */
    public function testViewConfigurationLoaderWhenWrongJsonMustThrowException()
    {
        // Arrange
        $wrongViewJsonMissingBodyStyleProperty = [
            "view" => "page_home",
            "title" => "Home",
            "styles" => [],
            "scripts" => []
        ];

        // Create a stub for the JsonLoader class
        $mockFile = $this->getMockBuilder(FileManager::class)
            ->setMethods(array('fileExists', 'jsonDecode'))
            ->getMock();

        $mockFile->method('fileExists')
            ->willReturn(true);

        $mockFile->method('jsonDecode')
            ->willReturn($wrongViewJsonMissingBodyStyleProperty);

        // Act
        new ViewConfigurationLoader($mockFile, "home");
    }

    public function testMenuConfigurationLoader()
    {
        // Arrange
        $menuJson = [
            [
                "title" => "page_home",
                "id" => "Home",
                "icon"=> "E871"
            ],
            [
                "title" => "page_products",
                "id" => "Products",
                "icon"=> "E872"
            ],
        ];

        // Create a stub for the JsonLoader class
        $mockFile = $this->getMockBuilder(FileManager::class)
            ->setMethods(array('fileExists', 'jsonDecode'))
            ->getMock();

        $mockFile->method('fileExists')
            ->willReturn(true);

        $mockFile->method('jsonDecode')
            ->willReturn($menuJson);

        try {
            // Act
            $jsonLoader = new MenuConfigurationLoader($mockFile);
            $actualData = $jsonLoader->getData();
        } catch (Exception $e) {
            echo $e->getMessage();
        }

        $this->assertEquals($menuJson, $actualData);
    }

    /**
     * @expectedException  Exception
     */
    public function testMenuConfigurationLoaderWhenContentLoadedIsNotMultidimensionalArrayMustThrowException()
    {
        // Arrange
        $menuJson = [
            "title" => "page_home",
            "id" => "Home",
            "icon"=> ""
        ];

        // Create a stub for the JsonLoader class
        $mockFile = $this->getMockBuilder(FileManager::class)
            ->setMethods(array('fileExists', 'jsonDecode'))
            ->getMock();

        $mockFile->method('fileExists')
            ->willReturn(true);

        $mockFile->method('jsonDecode')
            ->willReturn($menuJson);

        new MenuConfigurationLoader($mockFile);
    }
}
