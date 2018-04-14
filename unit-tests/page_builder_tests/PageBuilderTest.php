<?php

require_once("../../relative-paths.php");
require_once("../UtilityTest.php");
require_once(LIBRARY_PATH . "/build-page/PageBuilder.php");
require_once(UTILITY_PATH . "/File.php");

use PHPUnit\Framework\TestCase;

class PageBuilderTest extends TestCase
{
    use UtilityTest;

    protected $mockFile = null;
    protected $page = null;

    /**
     * Create test subject before test
     * @throws Exception
     */
    protected function setUp()
    {
        $_GET['page'] = 'home';

        // Create a stub for the JsonLoader class
        $this->mockFile = $this->getMockBuilder(File::class)
            ->setMethods(array('fileExists', 'jsonDecode', 'loadFileContent'))
            ->getMock();

        $this->mockFile->method('fileExists')
            ->willReturn(true);

        $this->mockFile->method('loadFileContent')
            ->willReturn("{ 'body_class_style'=>'some_style', 'title'=>'some_title' }");

        $this->mockFile->expects($this->at(3))
            ->method('jsonDecode')
            ->willReturn([
                'body_class_style' => 'some_style',
                'title' => 'some_title',
                'styles' => [],
                'scripts' => []
            ]);

        $this->mockFile->expects($this->at(6))
            ->method('jsonDecode')
            ->willReturn([
                [
                    "title" => "Home",
                    "id" => "home",
                    "icon" => "E871"
                ],
                [
                    "title" => "Products",
                    "id" => "products",
                    "icon" => "E8CB"
                ]
            ]);

        $this->mockFile->expects($this->at(9))
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
    }

    public function testProperCreation()
    {
        try {
            // Act
            $this->page = PageBuilder::MakePage($this->mockFile, $_GET);
        } catch (Exception $e) {
            echo $e->getMessage();
        }
        // Assert
        $this->assertEquals("some_style", $this->page->getBodyClass());

        // Clean
        $this->page->__destruct();
    }
    
    protected function tearDown()
    {
        $_GET = array();
    }
}
