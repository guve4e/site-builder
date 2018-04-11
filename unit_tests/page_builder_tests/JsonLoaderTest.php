<?php
/**
 * Tests CookieSetter Class.
 */

require_once ("../../config.php");
require_once (JSON_LOADER_PATH . "/JsonLoader.php");
require_once (JSON_LOADER_PATH . "/SiteConfigurationLoaderLoader.php");
require_once(JSON_LOADER_PATH . "/ViewConfigurationLoader.php");
require_once (JSON_LOADER_PATH . "/MenuConfigurationLoaderLoader.php");

use PHPUnit\Framework\TestCase;

class JsonLoaderTest extends TestCase
{
    protected $mockFile;

    protected function setUp()
    {
        // Create a stub for the JsonLoader class
        $this->mockFile = $this->getMockBuilder(File::class)
            ->setMethods(array('fileExists', 'jsonDecode', 'loadFileContent'))
            ->getMock();

        $this->mockFile->method('fileExists')
            ->willReturn(true);

        $this->mockFile->method('loadFileContent')
            ->willReturn("{ 'key'=>'value' }");

        $this->mockFile->method('jsonDecode')
            ->willReturn([ 'key'=>'value' ]);
    }

    public function testLoadJsonWhenFileExists()
    {
        try {
            // Act
            $jsonLoader = new JsonLoader($this->mockFile, "path/to/file");

            // Assert
            $actualData = $jsonLoader->getData();

            $this->assertEquals([ 'key'=>'value' ], $actualData);
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }

    public function testSiteConfigurationLoader()
    {
        // Arrange
        $siteJson = [
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
        $mockFile = $this->getMockBuilder(File::class)
            ->setMethods(array('fileExists', 'jsonDecode'))
            ->getMock();

        $mockFile->method('fileExists')
            ->willReturn(true);

        $mockFile->method('jsonDecode')
            ->willReturn($siteJson);

        try {
            // Act
            $jsonLoader = new SiteConfigurationLoader($mockFile);

            // Assert
            $actualData = $jsonLoader->getData();

            $this->assertEquals($siteJson, $actualData);
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }

    /**
     * @expectedException Exception
     */
    public function testSiteConfigurationLoaderMustThrowExceptionWhenWrongConfig()
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
        $mockFile = $this->getMockBuilder(File::class)
            ->setMethods(array('fileExists', 'jsonDecode'))
            ->getMock();

        $mockFile->method('fileExists')
            ->willReturn(true);

        $mockFile->method('jsonDecode')
            ->willReturn($siteJsonMissingStyles);

        // Act
        new SiteConfigurationLoader($mockFile);
    }

    public function testViewConfigurationLoader()
    {
        // Arrange
        $siteJson = [
            "view" => "page_home",
            "title" => "Home",
            "body_class_style"=> "",
              "styles" => [],
              "scripts" => []
        ];

        // Create a stub for the JsonLoader class
        $mockFile = $this->getMockBuilder(File::class)
            ->setMethods(array('fileExists', 'jsonDecode'))
            ->getMock();

        $mockFile->method('fileExists')
            ->willReturn(true);

        $mockFile->method('jsonDecode')
            ->willReturn($siteJson);

        try {
            // Act
            $jsonLoader = new ViewConfigurationLoader($mockFile, "home");

            // Assert
            $actualData = $jsonLoader->getData();

            $this->assertEquals($siteJson, $actualData);
        } catch (Exception $e) {
            echo $e->getMessage();
        }
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
        $mockFile = $this->getMockBuilder(File::class)
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
        $mockFile = $this->getMockBuilder(File::class)
            ->setMethods(array('fileExists', 'jsonDecode'))
            ->getMock();

        $mockFile->method('fileExists')
            ->willReturn(true);

        $mockFile->method('jsonDecode')
            ->willReturn($menuJson);

        try {
            // Act
            $jsonLoader = new MenuConfigurationLoader($mockFile);

            // Assert
            $actualData = $jsonLoader->getData();

            $this->assertEquals($menuJson, $actualData);
        } catch (Exception $e) {
            echo $e->getMessage();
        }
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
        $mockFile = $this->getMockBuilder(File::class)
            ->setMethods(array('fileExists', 'jsonDecode'))
            ->getMock();

        $mockFile->method('fileExists')
            ->willReturn(true);

        $mockFile->method('jsonDecode')
            ->willReturn($menuJson);

        new MenuConfigurationLoader($mockFile);
    }

    /**
     * @expectedException  Exception
     */
    public function testJsonLoaderWhenFileIsEmptyMustThrowException()
    {
        // Arrange
        // We want to simulate an empty config file
        $mockFile = $this->getMockBuilder(File::class)
            ->setMethods(array('fileExists', 'loadFileContent'))
            ->getMock();

        $mockFile->method('fileExists')
            ->willReturn(true);

        $mockFile->method('loadFileContent')
            ->willReturn("");

        // Act
        new JsonLoader($mockFile, "path/to/file");
    }

    /**
     * @expectedException  Exception
     */
    public function testLoadJsonMustThrowExceptionWhenFileDoesNotExists()
    {
        new JsonLoader(new File(), "path/to/file");
    }
}
