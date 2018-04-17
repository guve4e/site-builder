<?php
/**
 * Tests CookieSetter Class.
 */
require_once ("../../../relative-paths.php");
require_once ("../UtilityTest.php");
require_once (USER_SESSION_PATH . "/CookieSetter.php");

use PHPUnit\Framework\TestCase;

class CookieSetterTest extends TestCase
{
    use UtilityTest;

    /**
     * Create test subject before test
     */
    protected function setUp()
    {
        $_SERVER['REMOTE_ADDR'] = "REMOTE_ADDR";
        $_SERVER['HTTP_USER_AGENT'] = "HTTP_USER_AGENT";
        $_SERVER['HTTP_ACCEPT'] = "HTTP_ACCEPT";
        $_COOKIE['crystalpure-user'] = "mcsiljcincklsdncvklsdvisdn";
    }

    public function testSetCookie()
    {
        // Arrange
        // Create a stub for the CookieSetter Class class.
        try {
            $stub = $this->createMock(CookieSetter::class);
        } catch (ReflectionException $e) {
            echo $e->getMessage();
        }

        // Configure the stub.
        $stub->method('setCookie')
            ->willReturn("SomeHash");

        // Assert
        $this->assertSame( "SomeHash", $stub->setCookie());
    }

    public function testProperSettingOfHash() {
        // Arrange
        $cookie = new CookieSetter("SomeCookie", 2);

        try {

            // Act
            $this->invokeMethod($cookie, 'makeHash');

        } catch (ReflectionException $e) {
            echo "Reflection Problem!";
        }

        // Assert
        $actualHash = $cookie->getHash();
        $this->assertSame("cdf26213a150dc3ecb610f18f6b38b46", $actualHash);
    }

    protected function tearDown()
    {
        $_SERVER = array();
    }
}
