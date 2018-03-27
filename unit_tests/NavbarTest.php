<?php
/**
 * TODO
 *
 */
require_once ("../../config.php");
require_once ("../UtilityTest.php");
require_once(LIBRARY_PATH . "/constructor/Navbar.php");

use PHPUnit\Framework\TestCase;

class NavbarTest extends TestCase
{
    use UtilityTest;

    protected $navbar = null;

    /**
     * Create test subject before test
     */
    protected function setUp()
    {
        $this->navbar = Navbar::MakeNavbar("header_full");
    }

    /**
     * Test proper setting of members
     */
    public function testProperInitializationOfAttributes()
    {
        // Arrange
        $expectedHasSecondarySidebar = true;
        $expectedBodyClass = "header_full";

        // Act
        $hasSecondarySidebar = $this->getProperty($this->navbar, "hasSecondarySidebar");
        $bodyClass = $this->getProperty($this->navbar, "bodyClass");
        $hasSecondarySidebarGeter = $this->navbar->hasSecondarySidebar();

        // Assert
        $this->assertSame($expectedBodyClass, $bodyClass);
        $this->assertSame($expectedHasSecondarySidebar, $hasSecondarySidebar);
        $this->assertSame($expectedHasSecondarySidebar, $hasSecondarySidebarGeter);
    }
}
