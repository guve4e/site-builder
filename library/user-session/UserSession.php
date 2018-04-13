<?php
require_once ("IdentifyUser.php");
/**
 * Represents a session with the client.
 * It loads a session key from config file,
 * to be generic.
 *
 * Checks $_SESSION super-global, if contains
 * value for 'authenticated_user'. If it does,
 * it makes Authenticated user (User is retrieved
 * from backend services). If it does not, the user
 * is identified.
 */
class UserSession
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
     * Loads session key from
     * config.php file.
     *
     * @throws Exception
     */
    private function loadSessionKey()
    {
        // get info from the config file
        global $config;

        // set the session key
        $this->sessionKey = $config['session']['key'];

        // check
        if (is_null($this->sessionKey))
            throw new Exception("CAN'T read config file!");
    }

    /**
     * Loads the name of the cookie
     * from config.php file.
     * @throws Exception
     */
    private function loadCookieInfo()
    {
        // get info from the config file
        global $config;

        // set the cookie name and time
        $this->cookieName = $config['cookie']['name'];
        $this->cookieTime = $config['cookie']['time'];

        // check
        if (is_null($this->cookieName) || is_null($this->cookieTime))
            throw new Exception("CAN'T read config file!");
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
            IdentifyUser::IdentifyUser(
                new CookieSetter($this->cookieName, $this->cookieTime),
                new PhpHttpAdapter(new RestCall("Curl", new File)));
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
    private function __construct()
    {
        $this->loadSessionKey();
        $this->loadCookieInfo();
        $this->makeUser();
    }

    /**
     * Retrieves user from $_SESSION
     * super-global.
     * Called from almost every view.php
     * file.
     * @return user: Either the user is
     * identified or authenticated.
     * If not any of those, assert.
     * @static
     * @throws Exception
     */
    public static function getUserFromSession()
    {
        $user = null;
        if(isset($_SESSION['authenticated_user']))
            $user = $_SESSION['authenticated_user'];
        else if(isset($_SESSION['crystalpure_user']))
            $user = $_SESSION['crystalpure_user'];
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
     * @return UserSession
     * @throws Exception
     */
    public static function Session() : UserSession
    {
        static $inst = null;
        if ($inst === null) {
            $inst = new UserSession();
        }

        return $inst;
    }
}