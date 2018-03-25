<?php
/**
 * Tests AuthenticateUser Class.
 */

require_once ("../../../config.php");
require_once("../IdentifyUser.php");
require_once ("UtilityTest.php");
require_once ("../../Logger.php");

use PHPUnit\Framework\TestCase;

class AuthenticateUserTest extends TestCase
{

    use UtilityTest;

    protected $http;

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


    }

    public function testIdentifyNewUser() {
        // Arrange

        // Make sure $_SESSION and $_COOKIE are clean
        $_SESSION = array();
        $_COOKIE = array();

        try {


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
