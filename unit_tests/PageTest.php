<?php
/**
 * TODO
 *
 */
require_once ("../../config.php");
require_once ("../UtilityTest.php");
require_once(LIBRARY_PATH . "/constructor/Page.php");

use PHPUnit\Framework\TestCase;

class PageTest extends TestCase
{
    use UtilityTest;

    protected $page = null;

    /**
     * Create test subject before test
     */
    protected function setUp()
    {
        $user = new StdClass;
        $user->userId = 1001;
        $_GET['page'] = 'home';

        $this->page = Page::MakePage($_GET, $user);
    }

    /**
     * Test proper setting of members
     */
    public function testProperInitializationOfAttributes()
    {
        // Arrange
        $expectedViewName = 'home';
        $expectedPageTitle = null;
        $expectedBodyClass = "header_full";

        // Act
        $viewName = $this->getProperty($this->page, "viewName");
        $pageTitle = $this->getProperty($this->page, "pageTitle");
        $pageConfig = $this->getProperty($this->page, "pageConfig");

        $bodyClass = $this->page->getBodyClass();

        $view = $this->getProperty($this->page, "view");
        $navbar = $this->getProperty($this->page, "navbar");
        $menu = $this->getProperty($this->page, "menu");

        // Assert
        $this->assertTrue(is_object($view) && isset($view));
        $this->assertTrue(is_object($navbar) && isset($navbar));
        $this->assertTrue(is_object($menu) && isset($menu));
        $this->assertTrue(is_array($pageConfig) && isset($pageConfig));
        $this->assertSame($expectedPageTitle, $pageTitle);
        $this->assertSame($expectedViewName, $viewName);
        $this->assertSame($expectedBodyClass, $bodyClass);
    }
}
