<?php
/**
 * Tests AuthenticateUser Class.
 */

require_once ("../../../config.php");
require_once("../AuthenticateUser.php");
require_once ("UtilityTest.php");
require_once ("../../Logger.php");

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
        parent::setUp();

        // Mock user, simulates user retrieval from
        // back end services
        $this->user = new StdClass;
        $this->user->name = "Some Name";

        // Create a stub for the PhpHttpAdapter class
        $this->http = $this->getMockBuilder(PhpHttpAdapter::class)
            ->setMethods(array('setJsonData', 'getJsonData', 'send')) //tells mock builder which methods should be mocked
            ->getMock();

        $this->nav = $this->createMock(NavigateToLocation::class);
        $this->nav->method('navigate');
    }

    public function testIdentifyExistingUser() {
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

            // Assert
            $this->assertEquals(["authenticated_user" => $this->user], $_SESSION);

            // Clean
            $authenticateObject->__destruct();

        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }

    /**
     * @expectedException  Exception
     */
    public function testIdentifyUserMustThrowExceptionWhenFormFieldsAreNotSet() {
        $_GET = array();
        $_POST = array();

        AuthenticateUser::Authenticate($_GET, $_POST, $this->http, $this->nav);
    }

    /**
     * @expectedException  Exception
     */
    public function testIdentifyUserMustThrowExceptionWhenPasswordFormFieldsAreNotEqual() {
        $_GET = array();
        $_POST = array();
        $_GET['id'] = "new_user";
        $_POST['register_name'] = "John Doe";
        $_POST['register_email']= "johndoe@gmail.com";
        $_POST['register_password'] = "password";
        $_POST['register_password_repeat'] = "otherpassword";

        AuthenticateUser::Authenticate($_GET, $_POST, $this->http, $this->nav);
    }

    public function testIdentifyNewUser() {
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

            // Assert
            $this->assertEquals(["authenticated_user" => $this->user], $_SESSION);

            // Clean
            $authenticateObject->__destruct();

        } catch (Exception $e) {
            echo $e->getMessage();
        }
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
