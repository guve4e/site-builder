<?php
/**
 * TODO
 *
 */
require_once ("../../config.php");
require_once ("../UtilityTest.php");
require_once(LIBRARY_PATH . "/constructor/Menu.php");

use PHPUnit\Framework\TestCase;

class MenuTest extends TestCase
{
    use UtilityTest;

    protected $menu = null;

    /**
     * Create test subject before test
     */
    protected function setUp()
    {
        $this->menu = Menu::MakeMenu('home');
    }

    /**
     * Test proper setting of members
     */
    public function testProperInitialization()
    {
        // Arrange
        $expectedVewName = 'home';

        // Act
        $menuConfig = $this->getProperty($this->menu, "menuConfig");
        $viewName = $this->getProperty($this->menu, "viewName");

        // Assert
        $this->assertTrue(is_array($menuConfig) && isset($menuConfig));
        $this->assertSame($expectedVewName, $viewName);
    }
}
