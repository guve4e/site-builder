<?php
/**
 * TODO Test $viewKey
 *
 */
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
        $this->mockFile = $this->getMockBuilder(File::class)
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
                'scripts' => []
            ]);
    }

    public function testProperConstructionOnViewWhenVewHasNoKey()
    {
        // Arrange
        $expectedJSPath = VIEW_PATH . "/some_view/script.php";
        $expectedViewPath = VIEW_PATH . "/some_view/view.php";

        try {
            // Act
            $file = new View($this->mockFile, 'some_view');
        } catch (Exception $e) {
            echo $e->getMessage();
        }

        // Assert
        $this->assertEquals("some_title", $file->getViewTitle());
        $this->assertEquals("some_style", $file->getViewBodyClass());
        $this->assertEquals("some_view", $file->getViewName());
        $this->assertEquals($expectedJSPath, $file->getViewJSPath());
        $this->assertEquals($expectedViewPath, $file->getViewPath());

        // Clean
        $file->__destruct();
    }

    /**
     * @expectedException  Exception
     */
    public function testViewMustThrowExceptionIfFileDoesNotExists() {
        new View(new File(), "some_view");
    }

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

        $mockFile = $this->getMockBuilder(File::class)
            ->setMethods(array('fileExists', 'jsonDecode', 'loadFileContent'))
            ->getMock();

        $mockFile->method('fileExists')
            ->willReturn(true);

        $mockFile->method('loadFileContent')
            ->willReturn("{ 'body_class_style'=>'some_style', 'title'=>'some_title' }");

        $mockFile->method('jsonDecode')
            ->willReturn($expectedArray);

        try {
            // Act
            $menu = new Menu($mockFile, 'some_view');

            // Careful here we brake encapsulation using reflection
            $actualMenuConfig = $this->getProperty($menu, "menuConfig");
        } catch (Exception $e) {
            echo $e->getMessage();
        }

        // Assert
        $this->assertEquals($expectedArray, $actualMenuConfig);
        // Clean
        $menu->__destruct();
    }

    public function testProperConstructionOnNavbar()
    {
        try {
            // Act
            $menu = new Navbar($this->mockFile, 'some_body_class');

            // Careful here we brake encapsulation using reflection
            $actualBodyClass = $this->getProperty($menu, "bodyClass");
        } catch (Exception $e) {
            echo $e->getMessage();
        }

        // Assert
        $this->assertEquals("some_body_class", $actualBodyClass);
        // Clean
        $menu->__destruct();
    }
}
