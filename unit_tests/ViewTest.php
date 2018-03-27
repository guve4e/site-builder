<?php
/**
 * TODO Test $viewKey
 *
 */
require_once ("../config.php");
require_once ("UtilityTest.php");
require_once("../library/build_page/View.php");

use PHPUnit\Framework\TestCase;

class ViewTest extends TestCase
{
    use UtilityTest;

    protected $view = null;

    /**
     * Create test subject before test
     */
    protected function setUp()
    {


        // Arrange
//        $this->expectedViewJSPath = VIEW_PATH . "/home/script.php";
//        $this->expectedViewName = "home";
//        $this->expectedViewDir = VIEW_PATH . "/home";
//        $this->expectedViewPath = VIEW_PATH . "/home/view.php";
//        $this->expectedViewBodyClass = "";
//        $this->expectedViewTitle = "Home";
    }

    /**
     * Test proper setting of members.
     */
    public function testProperInitialization()
    {

        // Act

        $view = View::MakeView('home');


//        $viewJSPath = $this->getProperty($this->view, "viewJSPath");
//        $viewName = $this->getProperty($this->view, "viewName");
//        $viewDir = $this->getProperty($this->view, "viewDir");
//        $viewPath = $this->getProperty($this->view, "viewPath");
//        $viewBodyClass = $this->getProperty($this->view, "viewBodyClass");
//        $viewTitle = $this->getProperty($this->view, "viewTitle");
//        $viewConfig = $this->getProperty($this->view, "viewConfig");
//
//        // Assert
//        $this->assertTrue(is_array($viewConfig) && isset($viewConfig));
//        $this->assertSame($this->expectedViewJSPath, $viewJSPath);
//        $this->assertSame($this->expectedViewName, $viewName);
//        $this->assertSame($this->expectedViewDir, $viewDir);
//        $this->assertSame($this->expectedViewPath, $viewPath);
//        $this->assertSame($this->expectedViewBodyClass, $viewBodyClass);
//        $this->assertSame($this->expectedViewTitle, $viewTitle);
    }

    /**
     * Test public methods.
     */
    public function testInterface()
    {

        $viewName = $this->view->getViewName();
        $bodyClass = $this->view->getViewBodyClass();
        $viewPath = $this->view->getViewPath();
        $viewTitle = $this->view->getViewTitle();
        $viewJSPath = $this->view->getViewJSPath();

        $this->assertSame($this->expectedViewName, $viewName);
        $this->assertSame($this->expectedViewJSPath, $viewJSPath);
        $this->assertSame($this->expectedViewPath, $viewPath);
        $this->assertSame($this->expectedViewBodyClass, $bodyClass);
        $this->assertSame($this->expectedViewTitle, $viewTitle);

    }
}
