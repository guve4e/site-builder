<?php
/**
 * TODO Test $viewKey
 *
 */
require_once("../../config.php");
require_once("../UtilityTest.php");
require_once("../../library/build-page/View.php");
require_once("../../library/build-page/Menu.php");
require_once("../../library/build-page/Navbar.php");

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

    public function testProperConstructionOnViewWhenVewHasNoKey()
    {
        // Arrange
        $expectedJSPath = VIEW_PATH . "/some_view/script.php";
        $expectedViewPath = VIEW_PATH . "/some_view/view.php";

        try {
            // Act
            $file = View::MakeView($this->mockFile, 'some_view');
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
        View::MakeView(new File(), "some_view");
    }

    public function testProperConstructionOnMenu()
    {
        try {
            // Act
            $menu = Menu::MakeMenu($this->mockFile, 'some_menu');

            // Careful here we brake encapsulation using reflection
            $actualMenuConfig = $this->getProperty($menu, "menuConfig");
        } catch (Exception $e) {
            echo $e->getMessage();
        }

        // Assert
        $this->assertEquals(['body_class_style' => 'some_style', 'title' => 'some_title'], $actualMenuConfig);
        // Clean
        $menu->__destruct();
    }

    /**
     * @expectedException  Exception
     */
    public function testMenuMustThrowExceptionIfFileDoesNotExists()
    {
        Menu::MakeMenu(new File(), "some_view");
    }

    public function testProperConstructionOnNavbar()
    {
        try {
            // Act
            $menu = Navbar::MakeNavbar($this->mockFile, 'some_body_class');

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

    /**
     * @expectedException  Exception
     */
    public function testNavbarMustThrowExceptionIfFileDoesNotExists()
    {
        Menu::MakeMenu(new File(), "some_view");
    }
}
