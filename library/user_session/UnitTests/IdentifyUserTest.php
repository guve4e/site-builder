<?php
/**
 * Tests CookieSetter Class.
 */

require_once ("../../../config.php");
require_once("../IdentifyUser.php");
require_once ("UtilityTest.php");
require_once ("../../Logger.php");

use PHPUnit\Framework\TestCase;

class IdentifyUserTest extends TestCase
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
    }

    public function testIdentifyNewUser() {
        // Arrange

        // Mock user, simulates user retrieval from
        // back end services
        $user = new StdClass;
        $user->name = "Some Name";

        // Make sure $_SESSION and $_COOKIE are clean
        $_SESSION = array();
        $_COOKIE = array();

        try {

            // Create a stub for the PhpHttpAdapter class
            $http = $this->getMockBuilder(PhpHttpAdapter::class)
                ->setMethods(array('setJsonData', 'getJsonData', 'send')) // this line tells mock builder which methods should be mocked
                ->getMock();

            // Create a stub for the CookieSetter Class class.
            $cookie = $this->createMock(CookieSetter::class);

            $cookie->method('gethash')
                ->willReturn("mcsiljcincklsdncvklsdvisdn");

            // Mock rhe setCookie method that actually creates a cookie on the
            // users computer. Since this method is mocked, lets manually update
            // $_COOKIE super-global
            $cookie->method('setCookie')
            ->willReturnCallback(function (){$_COOKIE["some_website_user"] = "mcsiljcincklsdncvklsdvisdn";});


            $cookie->method('getCookieName')
                ->willReturn("some_website_user");

            $http->method('getJsonData')
                ->willReturn($user);

            $identifyUser = IdentifyUser::IdentifyUser($cookie, $http);
            $actualHash = $this->getProperty($identifyUser, "userHash");
            $actualUser = $this->getProperty($identifyUser, 'user');

            // Assert
            $this->assertSame("mcsiljcincklsdncvklsdvisdn", $actualHash, "Testing Expected Hash");
            $this->assertSame($user, $actualUser, 'Testing Expected User');
            $this->assertSame($_SESSION['some_website_user'], $user, "Testing proper setting of _SESSION");

            // Clean resources Testing only

            $identifyUser->__destruct();
            unset($identifyUser);
            $identifyUser = null;

        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }

    public function testIdentifyExistingUser() {
        // Arrange

        // Mock user, simulates user retrieval from
        // back end services
        $user = new StdClass;
        $user->name = "Some Name";

        // Make sure $_SESSION is clean and $_COOKIE is set
        $_SESSION = array();
        $_COOKIE["some_website_user"] = "mcsiljcincklsdncvklsdvisdn";

        try {

            // Create a stub for the PhpHttpAdapter class
            $http = $this->getMockBuilder(PhpHttpAdapter::class)
                ->setMethods(array('setJsonData', 'getJsonData', 'send')) // this line tells mock builder which methods should be mocked
                ->getMock();

            // Create a stub for the CookieSetter Class class.
            $cookie = $this->createMock(CookieSetter::class);

            $cookie->method('gethash')
                ->willReturn("mcsiljcincklsdncvklsdvisdn");


            $cookie->method('getCookieName')
                ->willReturn("some_website_user");

            $http->method('getJsonData')
                ->willReturn($user);

            // Act
            $identifyUser = IdentifyUser::IdentifyUser($cookie, $http);
            $actualHash = $this->getProperty($identifyUser, "userHash");
            $actualUser = $this->getProperty($identifyUser, 'user');

            // Assert
            $this->assertSame("mcsiljcincklsdncvklsdvisdn", $actualHash, "Testing Expected Hash");
            $this->assertEquals($user, $actualUser, 'Testing Expected User');
            $this->assertSame($_SESSION['some_website_user'], $user, "Testing proper setting of _SESSION");

        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }

    protected function tearDown()
    {
        $_SERVER = array();
        $_SESSION = array();
        $_COOKIE = array();
    }
}
