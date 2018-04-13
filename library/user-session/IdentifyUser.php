<?php
/**
 * Used for user identification.
 *
 * IdentifyUser holding two properties:
 * 1. cookie object (Cookie Class)
 * 2. hash (string produced by combining the
 * $_SERVER['REMOTE_ADDR'] + $_SERVER['HTTP_USER_AGENT'] + $_SERVER['HTTP_ACCEPT']
 * This class looks trough the users machine and checks for cookie with name "web_user".
 * If NOT found, retrieves the needed info from _SERVER super-global and produces a hash.
 * This hash is stored in the users machine in cookie and it adds the user in a table.
 * If the cookie is already there it retrieves the has and asks the web-api for user with this hash.
 */


require_once("CookieSetter.php");
require_once("SessionHelper.php");
require_once(HTTP_PATH . "/PhpHttpAdapter.php");

class IdentifyUser
{
    use SessionHelper;

    /**
     * @var object
     * Makes a cookie
     * on client's computer.
     */
    private $cookieSetter;

    /**
     *
     * @var string
     * Hash produced for each
     * identified user.
     */
    private $userHash;

    /**
     * @var object
     * Used to send HTTP requests.
     */
    private $http;

    /**
     * @var
     * User object retrieved
     * from back end services.
     */
    private $user;

    /**
     * @var null
     * Instance variable
     * used to simulate Singleton.
     */
    private static $instance = null;

    /**
     * Identify constructor.
     * @param CookieSetter $cookieSetter
     * @param PhpHttpAdapter $http
     * @throws Exception
     */
    private function __construct(CookieSetter $cookieSetter, PhpHttpAdapter $http)
    {
        // initialize CookieSetter Object
        $this->cookieSetter = $cookieSetter;

        // initialize RestCall object
        $this->http = $http;

        // identify user
        $this->identify();
    }

    /**
     * Calls Web API
     * to retrieve the user information.
     *
     * @throws Exception
     */
    private function retrieveUser()
    {
        if(!isset($this->http))
            throw new Exception("Http Adapter is not set");

        // set method
        $this->http->setMethod('GET')
            ->setParameter($this->userHash);

        $user = $this->http->send();

        return $user;
    }

    /**
     * Compacts an object, with member
     * called hash. This info is needed
     * for back end services to make
     * new user.
     * @throws Exception
     */
    private function makeData() : array
    {
        $hash =  $this->cookieSetter->getHash();
        // check for proper hash
        if(!isset($hash))
            throw new Exception("Hash was not created");


        return ["hash" => $this->cookieSetter->getHash()];
    }

    /**
     * Calls Web API to make new User.
     * Note, no parameter needed
     * for this rest call.
     * @access private
     * @throws Exception
     */
    private function setUser()
    {
        if(!isset($this->http))
            throw new Exception("Http Adapter is not set");

        // set up new cookie on
        // client's computer
        $this->cookieSetter->setCookie();

        $data = $this->makeData();
        // set method
        $this->http->setMethod('POST')
            ->setJsonData($data);
        // send request
        $this->http->send();
    }

    /**
     * Checks $_COOKIE super-global for valid user.
     * If user is valid it calls retrieveUser(), to
     * retrieve the already known user from back end
     * services. User comes with user id and items in
     * shopping cart. If user is not identified, setUser
     * is called to make a new user with cookie and then
     * getUser is called  to retrieve info for the newly
     * created user from back end services.
     *
     * @throws Exception
     */
    private function identify()
    {
        if (isset($_COOKIE[$this->cookieSetter->getCookieName()])) // existing user
        {
            // get hash from cookie
            $this->userHash = $_COOKIE[$this->cookieSetter->getCookieName()];
        }
        else // new user
        {
            $this->setUser();
            // get hash from cookie

            $this->userHash = $_COOKIE[$this->cookieSetter->getCookieName()];
        }

        // retrieve user from web-api
        $this->user = $this->retrieveUser();

        // save to session
        $this->saveUserInSession($this->cookieSetter->getCookieName(), $this->user);
    }

    /**
     * Singleton.
     * @access public
     * @param CookieSetter $cookieSetter
     * @param PhpHttpAdapter $http
     * @return IdentifyUser
     * @throws Exception
     */
    public static function IdentifyUser(CookieSetter $cookieSetter, PhpHttpAdapter $http) : IdentifyUser
    {
        if (self::$instance === null) {
            self::$instance = new IdentifyUser($cookieSetter, $http);
        }

        return self::$instance;
    }

    public function __destruct()
    {
        unset($this->cookieSetter);
        unset($this->userHash);
        unset($this->http);
        self::$instance = null;
    }
}