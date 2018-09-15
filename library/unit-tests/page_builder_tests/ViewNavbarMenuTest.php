<?php

require_once ("../../../relative-paths.php");
require_once ("../UtilityTest.php");
require_once (BUILD_PATH . "/View.php");
require_once (BUILD_PATH . "/Menu.php");
require_once (BUILD_PATH . "/Navbar.php");

use PHPUnit\Framework\TestCase;

class ViewNavbarMenuTest extends TestCase
{
    use UtilityTest;

    protected $mockFile;

    /**
     * Create test subject before test
     */
    protected function setUp()
    {
        $this->mockFile = $this->getMockBuilder(FileManager::class)
            ->setMethods(array('fileExists', 'jsonDecode', 'loadFileContent'))
            ->getMock();

        $this->mockFile->method('fileExists')
            ->willReturn(true);

        $this->mockFile->method('loadFileContent')
            ->willReturn("{ 'body_class_style'=>'some_style', 'title'=>'some_title' }");

        $this->mockFile->method('jsonDecode')
            ->willReturn([
                'body_class_style' => 'some_style',
                'title' => 'some_title',
                "full_screen" => true,
                'styles' => [],
                'scripts' => [],
                'footer' => "some-view-footer"
            ]);
    }

    /**
     * @throws Exception
     */
    public function testProperConstructionOnViewWhenVewHasNoKey()
    {
        // Arrange
        $expectedJSPath = VIEW_PATH . "/some_view/script.php";
        $expectedViewPath = VIEW_PATH . "/some_view/view.php";

        // Act
        $file = new View($this->mockFile, 'some_view');

        // Assert
        $this->assertEquals("some_title", $file->getTitle(), "Asserts right vew title");
        $this->assertEquals("some_style", $file->getBodyClass(), "Asserts right body class");
        $this->assertEquals("some_view", $file->getName(), "Asserts right vew name");
        $this->assertEquals($expectedJSPath, $file->getViewJSPath());
        $this->assertEquals($expectedViewPath, $file->getViewPath());
        $this->assertEquals(true, $file->hasFooter());
        $this->assertEquals("/some-view-footer.php", $file->getViewFooterName());

        // Clean
        $file->__destruct();
    }

    /**
     * @expectedException  Exception
     */
    public function testViewMustThrowExceptionIfFileDoesNotExists() {
        new View(new FileManager(), "some_view");
    }

    /**
     * @throws ReflectionException
     */
    public function testProperConstructionOnMenu()
    {
        // Arrange
        $expectedArray = [
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
        ];

        $mockFile = $this->getMockBuilder(FileManager::class)
            ->setMethods(array('fileExists', 'jsonDecode', 'loadFileContent'))
            ->getMock();

        $mockFile->method('fileExists')
            ->willReturn(true);

        $mockFile->method('loadFileContent')
            ->willReturn("{ 'body_class_style'=>'some_style', 'title'=>'some_title' }");

        $mockFile->method('jsonDecode')
            ->willReturn($expectedArray);

        $menu = new Menu($mockFile);

        // Careful here we brake encapsulation using reflection
        $actualMenuConfig = $this->getProperty($menu, "menuConfig");

        // Assert
        $this->assertEquals($expectedArray, $actualMenuConfig);
        // Clean
        $menu->__destruct();
    }

    /**
     * @throws ReflectionException
     */
    public function testProperConstructionOnNavbar()
    {
        $menu = new Navbar($this->mockFile, 'some_body_class');

        // Careful here we brake encapsulation using reflection
        $actualBodyClass = $this->getProperty($menu, "bodyClass");

        // Assert
        $this->assertEquals("some_body_class", $actualBodyClass);
        // Clean
        $menu->__destruct();
    }
}
