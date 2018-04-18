<?php
/**
 * Tests IdentifyUser Class.
 */

require_once ("../../../relative-paths.php");
require_once ("../UtilityTest.php");
require_once(USER_SESSION_PATH . "/UserIdentifier.php");
require_once (UTILITY_PATH . "/Logger.php");
require_once (DATA_RESOURCE_PATH . "/User.php");

use PHPUnit\Framework\TestCase;

class IdentifyUserTest extends TestCase
{
    use UtilityTest;

    protected $userObject;

    protected $cookie;

    protected $user;

    /**
     * Create test subject before test
     */
    protected function setUp()
    {
        // Mock user, simulates user retrieval from
        // back end form
        $this->user = new StdClass;
        $this->user->name = "Some Name";

        $_SERVER['REMOTE_ADDR'] = "REMOTE_ADDR";
        $_SERVER['HTTP_USER_AGENT'] = "HTTP_USER_AGENT";
        $_SERVER['HTTP_ACCEPT'] = "HTTP_ACCEPT";

        $this->userObject = $this->getMockBuilder(User::class)
            ->setMethods(['create', 'get'])
            ->getMock();

        $this->cookie = $this->createMock(CookieSetter::class);

        $this->cookie->method('gethash')
            ->willReturn("mcsiljcincklsdncvklsdvisdn");
    }

    public function testIdentifyNewUser() {
        // Arrange

        // Make sure $_SESSION and $_COOKIE are clean
        $_SESSION = array();
        $_COOKIE = array();

        try {
            // Mock the setCookie method that actually creates a cookie on the
            // users computer. Since this method is mocked, lets manually update
            // $_COOKIE super-global
            $this->cookie->method('setCookie')
            ->willReturnCallback(function (){$_COOKIE["some_website_user"] = "mcsiljcincklsdncvklsdvisdn";});

            $this->cookie->method('getCookieName')
                ->willReturn("some_website_user");

            $this->userObject->method('get')
                ->willReturn($this->user);

            $identifyUser = UserIdentifier::IdentifyUser($this->cookie, $this->userObject);
            $actualHash = $this->getProperty($identifyUser, "userHash");
            $actualUser = $this->getProperty($identifyUser, 'user');

        } catch (Exception $e) {
            echo $e->getMessage();
        }

        // Assert
        $this->assertSame("mcsiljcincklsdncvklsdvisdn", $actualHash, "Testing Expected Hash");
        $this->assertSame($this->user, $actualUser, 'Testing Expected User');
        $this->assertSame($_SESSION['some_website_user'], $this->user, "Testing proper setting of _SESSION");

        // Clean resources / Singleton object
        $identifyUser->__destruct();
    }

    public function testIdentifyExistingUser() {
        // Arrange

        // Make sure $_SESSION is clean and $_COOKIE is set
        $_SESSION = array();
        $_COOKIE["some_website_user"] = "mcsiljcincklsdncvklsdvisdn";

        try {

            $this->cookie->method('gethash')
                ->willReturn("mcsiljcincklsdncvklsdvisdn");


            $this->cookie->method('getCookieName')
                ->willReturn("some_website_user");

            $this->userObject->method('get')
                ->willReturn($this->user);

            // Act
            $identifyUser = UserIdentifier::IdentifyUser($this->cookie, $this->userObject);
            $actualHash = $this->getProperty($identifyUser, "userHash");
            $actualUser = $this->getProperty($identifyUser, 'user');

        } catch (Exception $e) {
            echo $e->getMessage();
        }

        // Assert
        $this->assertSame("mcsiljcincklsdncvklsdvisdn", $actualHash, "Testing Expected Hash");
        $this->assertEquals($this->user, $actualUser, 'Testing Expected User');
        $this->assertSame($_SESSION['some_website_user'], $this->user, "Testing proper setting of _SESSION");

        // Clean resources / Singleton object
        $identifyUser->__destruct();

    }

    protected function tearDown()
    {
        $_SERVER = array();
        $_SESSION = array();
        $_COOKIE = array();
    }
}