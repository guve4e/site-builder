<?php

/**
 * Represents a session with the client.
 * It loads a session key from config file,
 * to be generic.
 *
 * Checks $_SESSION super-global, if contains
 * value for 'authenticated_user'. If it does,
 * it makes Authenticated user (User is retrieved
 * from backend form). If it does not, the user
 * is identified.
 */

require_once ("IdentifyUser.php");
require_once (DATA_RESOURCE_PATH . "/User.php");

final class UserSession
{
    /**
     * @var string
     */
    private $sessionKey = null;

    /**
     * @var string
     * The name of the cookie.
     */
    private $cookieName = "";

    /**
     * @var integer
     * The time cookie needs to be active.
     */
    private $cookieTime;

    /**
     * @var SiteConfigurationLoader
     * Provides configuration details.
     */
    private $configuration;

    /**
     * Loads session key from
     * relative-paths.php file.
     *
     * @throws Exception
     */
    private function loadSessionConfiguration()
    {
        $this->sessionKey = $this->configuration->session->key;
        $this->cookieName = $this->configuration->cookie->name;
        $this->cookieTime = $this->configuration->cookie->time;
    }

    /**
     * Checks if user is authenticated.
     * If she is not, it makes an object
     * of identified user.
     * @throws Exception
     */
    private function makeUser()
    {
        if(!$this->isUserAuthenticated())
        {
            $cookieSetter = new CookieSetter($this->cookieName, $this->cookieTime);
            IdentifyUser::IdentifyUser($cookieSetter, new User());
        }
    }

    /**
     *
     *
     * @return bool: True if user is authenticated,
     * false otherwise.
     */
    private function isUserAuthenticated()
    {
        // IdentifiedUser can be saved in the session already
        return isset($_SESSION['authenticated_user']);
    }

    /**
     * Session constructor.
     *
     * @throws Exception
     */
    private function __construct(StdClass $configuration)
    {

        if (!isset($configuration))
            throw new Exception("Bad parameter in UserSession constructor!");

        $this->configuration = $configuration;

        $this->loadSessionConfiguration();
        $this->makeUser();
    }

    /**
     * Retrieves user from $_SESSION
     * super-global.
     * Called from almost every view.php
     * file.
     * @return user: identified user
     * If not any of those, assert.
     * @throws Exception
     */
    public function getUserFromSession()
    {
        $user = null;
        if(isset($_SESSION[$this->sessionKey]))
            $user = $_SESSION[$this->sessionKey];
        else
            throw new Exception("User is not SET!");

        return $user;
    }

    /**
     * Log out
     */
    public static function logout() {
        // delete cookie
        setcookie(session_name(), '', time() - 2592000, '/');
        // remove all session variables
        session_unset();
        $_SESSION = array();
        // destroy the session
        session_destroy();
    }

    /**
     * Singleton.
     * @access public
     * @param StdClass $configuration
     * @return UserSession
     * @throws Exception
     */
    public static function Session(StdClass $configuration) : UserSession
    {
        static $inst = null;
        if ($inst === null) {
            $inst = new UserSession($configuration);
        }

        return $inst;
    }
}