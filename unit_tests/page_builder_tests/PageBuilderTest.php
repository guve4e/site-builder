<?php

require_once("../../config.php");
require_once("../UtilityTest.php");
require_once(LIBRARY_PATH . "/build_page/PageBuilder.php");
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

        $this->mockFile->method('jsonDecode')
            ->willReturn(['body_class_style' => 'some_style', 'title' => 'some_title']);
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

    /**
     * @expectedException  Exception
     */
    public function testPageBuilderMustThrowExceptionWhenWrongGETSuperglobal()
    {
        $_GET = array();
        $_GET['key'] = "value";

        PageBuilder::MakePage($this->mockFile, $_GET);
    }

    protected function tearDown()
    {
        $_GET = array();
    }
}
