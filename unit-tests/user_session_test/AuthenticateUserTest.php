<?php
/**
 * Tests AuthenticateUser Class.
 */

require_once("../../relative-paths.php");
require_once ("../UtilityTest.php");
require_once (USER_SESSION_PATH . "/AuthenticateUser.php");
require_once (UTILITY_PATH . "/Logger.php");

use PHPUnit\Framework\TestCase;

class AuthenticateUserTest extends TestCase
{
    use UtilityTest;

    protected $http;

    protected $nav;

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

        try {

            // Create a stub for the PhpHttpAdapter class
            $this->http = $this->getMockBuilder(PhpHttpAdapter::class)
                ->setConstructorArgs([new RestCall("Curl", new File)])
                ->setMethods(array('setJsonData', 'getJsonData', 'send')) //tells mock builder which methods should be mocked
                ->getMock();

            $this->nav = $this->createMock(NavigateToLocation::class);
        } catch (ReflectionException $e) {
            echo $e->getMessage();
        }

        $this->nav->method('navigate');
    }

    public function testAuthenticateExistingUser() {
        // Arrange

        // Make sure $_SESSION and $_COOKIE are clean
        $_SESSION = array();
        $_COOKIE = array();
        $_GET['id'] = "existing_user";
        $_POST['login_username'] = "username";
        $_POST['login_password']= "password";

        try {

            $this->http->method('getJsonData')
                ->willReturn($this->user);
            // Act
            $authenticateObject = AuthenticateUser::Authenticate($_GET, $_POST, $this->http, $this->nav);

        } catch (Exception $e) {
            echo $e->getMessage();
        }

        // Assert
        $this->assertEquals(["authenticated_user" => $this->user], $_SESSION);

        // Clean
        $authenticateObject->__destruct();
    }

    /**
     * @expectedException  Exception
     */
    public function testAuthenticateUserMustThrowExceptionWhenFormFieldsAreNotSet() {
        $_GET = array();
        $_POST = array();

        AuthenticateUser::Authenticate($_GET, $_POST, $this->http, $this->nav);
    }

    /**
     * @expectedException  Exception
     */
    public function testAuthenticateUserMustThrowExceptionWhenPasswordFormFieldsAreNotEqual() {
        $_GET = array();
        $_POST = array();
        $_GET['id'] = "new_user";
        $_POST['register_name'] = "John Doe";
        $_POST['register_email']= "johndoe@gmail.com";
        $_POST['register_password'] = "password";
        $_POST['register_password_repeat'] = "otherpassword";

        AuthenticateUser::Authenticate($_GET, $_POST, $this->http, $this->nav);
    }

    public function testAuthenticateNewUser() {
        // Arrange

        // Make sure $_SESSION and $_COOKIE are clean
        $_SESSION = array();
        $_COOKIE = array();
        $_GET['id'] = "new_user";
        $_POST['register_name'] = "John Doe";
        $_POST['register_email']= "johndoe@gmail.com";
        $_POST['register_password'] = "password";
        $_POST['register_password_repeat'] = "password";

        try {

            $this->http->method('getJsonData')
                ->willReturn($this->user);

            // Act
            $authenticateObject = AuthenticateUser::Authenticate($_GET, $_POST, $this->http, $this->nav);

        } catch (Exception $e) {
            echo $e->getMessage();
        }

        // Assert
        $this->assertEquals(["authenticated_user" => $this->user], $_SESSION);

        // Clean
        $authenticateObject->__destruct();
    }

    public function testAuthenticateNewUserWhenFailsAuthentication() {
        // Arrange

        // Make sure $_SESSION and $_COOKIE are clean
        $_SESSION = array();
        $_COOKIE = array();
        $_GET['id'] = "new_user";
        $_POST['register_name'] = "John Doe";
        $_POST['register_email']= "johndoe@gmail.com";
        $_POST['register_password'] = "password";
        $_POST['register_password_repeat'] = "password";

        try {

            $this->http->method('getJsonData')
                ->willReturn(null);

            // Act
            $authenticateObject = AuthenticateUser::Authenticate($_GET, $_POST, $this->http, $this->nav);
        } catch (Exception $e) {
            echo $e->getMessage();
        }

        // Assert
        $this->assertEquals([], $_SESSION);

        // Clean
        $authenticateObject->__destruct();
    }

    protected function tearDown()
    {
        $_SERVER = array();
        $_SESSION = array();
        $_COOKIE = array();
        $_GET = array();
        $_POST = array();
    }
}
