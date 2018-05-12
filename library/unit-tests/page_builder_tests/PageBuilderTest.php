<?php

require_once ("../../../relative-paths.php");
require_once ("../UtilityTest.php");
require_once (LIBRARY_PATH . "/page/Page.php");
require_once (UTILITY_PATH . "/FileManager.php");
require_once (BUILD_PATH . "/PageBuilder.php");
require_once (BUILD_PATH . "/PageDirector.php");


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

        $this->mockFile = $this->getMockBuilder(FileManager::class)
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
                'full_screen' => true,
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

    public function testPrint()
    {
        try {
            // Act

            $pageBuilder = new PageBuilder();
            $pageDirector = new PageDirector($pageBuilder);
            $pageDirector->buildPage();


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
        // remove all session variables
        session_unset();
        $_SESSION = array();
        // destroy the session
        session_destroy();
    }
}
