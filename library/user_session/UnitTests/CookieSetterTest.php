<?php
/**
 * Tests CookieSetter Class.
 */
require_once("../CookieSetter.php");
require_once ("UtilityTest.php");

use PHPUnit\Framework\TestCase;

class CookieTest extends TestCase
{

    use UtilityTest;

    /**
     * Create test subject before test
     */
    protected function setUp()
    {
        parent::setUp();

        $_SERVER['REMOTE_ADDR'] = "REMOTE_ADDR";
        $_SERVER['HTTP_USER_AGENT'] = "HTTP_USER_AGENT";
        $_SERVER['HTTP_ACCEPT'] = "HTTP_ACCEPT";
        $_COOKIE['crystalpure-user'] = "mcsiljcincklsdncvklsdvisdn";
    }

    public function testSetCookie()
    {
        // Arrange
        // Create a stub for the CookieSetter Class class.
        $stub = $this->createMock(CookieSetter::class);

        // Configure the stub.
        $stub->method('setCookie')
            ->willReturn("SomeHash");

        // Act

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
