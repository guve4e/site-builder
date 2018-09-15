<?php

require_once ("../../../relative-paths.php");
require_once ("../UtilityTest.php");
require_once (LIBRARY_PATH . "/page/Page.php");
require_once (UTILITY_PATH . "/FileManager.php");
require_once (BUILD_PATH . "/IPageBuilder.php");
require_once (BUILD_PATH . "/PageDirector.php");
require_once (BUILD_PATH . "/PageBuilderFactory.php");
require_once (BUILD_PATH . "/IdentificationPageBuilder.php");
require_once (BUILD_PATH . "/FullScreenPageBuilder.php");

use PHPUnit\Framework\TestCase;

class PageBuilderTest extends TestCase
{
    /**
     * @throws Exception
     */
    public function testFullScreenPageBuilder()
    {
        // Arrange
        $_GET['page'] = 'home';

        $mockFile = $this->getMockBuilder(FileManager::class)
            ->setMethods(array('fileExists', 'jsonDecode', 'loadFileContent'))
            ->getMock();

        $mockFile->method('fileExists')
            ->willReturn(true);

        $mockFile->method('loadFileContent')
            ->willReturn("{ 'body_class_style'=>'some_style', 'title'=>'some_title' }");

        $mockFile->expects($this->at(3))
            ->method('jsonDecode')
            ->willReturn([
                'body_class_style' => 'some_style',
                'title' => 'some_title',
                'full_screen' => true,
                'styles' => [],
                'scripts' => []
            ]);

        $mockFile->expects($this->at(6))
            ->method('jsonDecode')
            ->willReturn([
                "title" => "Site Title",
                "styles" => [
                    [
                        "rel" => "stylesheet",
                        "type" => "",
                        "src" => "bower",
                        "path" => "/uikit/css/",
                        "name" => "uikit.almost-flat",
                        "media" => "all",
                        "min" => true
                    ],
                    [
                        "rel" => "stylesheet",
                        "type" => "",
                        "src" => "bower",
                        "path" => "/uikit/css/themes/",
                        "name" => "uikit.themes_combined-flat",
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
                        "name" => "admin_common",
                        "min" => true
                    ]
                ]
            ]);

        $fullScreenPageBuilder = PageBuilderFactory::MakePageBuilder($mockFile, $_GET);

        // Assert
        $this->assertInstanceOf('FullScreenPageBuilder', $fullScreenPageBuilder);
    }

    /**
     * @throws Exception
     */
    public function testIdentificationPageBuilder()
    {
        // Arrange
        $_GET['page'] = 'home';

        $mockFile = $this->getMockBuilder(FileManager::class)
            ->setMethods(array('fileExists', 'jsonDecode', 'loadFileContent'))
            ->getMock();

        $mockFile->method('fileExists')
            ->willReturn(true);

        $mockFile->method('loadFileContent')
            ->willReturn("{ 'body_class_style'=>'some_style', 'title'=>'some_title' }");

        $mockFile->expects($this->at(3))
            ->method('jsonDecode')
            ->willReturn([
                'body_class_style' => 'some_style',
                'title' => 'some_title',
                'full_screen' => false,
                'styles' => [],
                'scripts' => []
            ]);

        $mockFile->expects($this->at(6))
            ->method('jsonDecode')
            ->willReturn([
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
            ]);

        $mockFile->expects($this->at(9))
            ->method('jsonDecode')
            ->willReturn([
                "title" => "Site Title",
                "styles" => [
                    [
                        "rel" => "stylesheet",
                        "type" => "",
                        "src" => "bower",
                        "path" => "/uikit/css/",
                        "name" => "uikit.almost-flat",
                        "media" => "all",
                        "min" => true
                    ],
                    [
                        "rel" => "stylesheet",
                        "type" => "",
                        "src" => "bower",
                        "path" => "/uikit/css/themes/",
                        "name" => "uikit.themes_combined-flat",
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
                        "name" => "admin_common",
                        "min" => true
                    ]
                ]
            ]);

        $identificationPageBuilder = PageBuilderFactory::MakePageBuilder($mockFile, $_GET);

        // Assert
        $this->assertInstanceOf('IdentificationPageBuilder', $identificationPageBuilder);
    }
}
