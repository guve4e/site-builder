<?php
/**
 * Authenticate User.
 */

require_once("SessionHelper.php");
require_once(HTTP_PATH . "/PhpHttpAdapter.php");

class NavigateToLocation
{
    /**
     * Navigates to the specified path.
     * @throws Exception
     */
    public function Navigate(string $path)
    {
        if ($path == "") throw new Exception("Path must be specified!");
        header("Location: " . $this->path);
    }
}

class AuthenticateUser
{
    use SessionHelper;

    /**
     * @var string
     * Where to go on success.
     */
    private $successPath = './?page=home';

    /**
     * @var string
     * Where to go on failure.
     */
    private $failurePath = 'login.php';

    /**
     * @var string
     * User's username.
     */
    private $username;

    /**
     * @var string
     * User's password.
     */
    private $password;

    /**
     * @var string
     * User's email.
     */
    private $registerEmail;

    /**
     * @var string
     * User's register name.
     * We keep separate attribute,
     * if the user is new.
     */
    private $registerName;

    /**
     * @var string
     * We keep separate attribute
     * if the user is new.
     */
    private $registerPassword;

    /**
     * @var object
     * Used to send http requests.
     */
    private $http;

    /**
     * @var object
     * Used to jump from php file to other
     */
    private $nav;

    /**
     * @var null
     * Instance variable
     * used to simulate Singleton.
     */
    static private $instance = null;

    /**
     * Navigates to successPath if authenticated,
     * and to failurePath if not.
     * @param bool $isAuthenticated
     * @throws Exception
     */
    private function navigate(bool $isAuthenticated)
    {
        if ($isAuthenticated) {
            $this->nav->navigate($this->successPath);
        } else {
            $this->nav->navigate($this->failurePath . "?error=authorized");
        }
    }

    /**
     * Given user object, it decides if it is valid
     * or not.
     * Stores the object in session and
     * then it navigates to fail or success
     * place.
     *
     * @param $user : object, representing User.
     * Retrieved form back-end services.
     * @throws Exception
     */
    private function loadUserInSession($user)
    {
        // check for success
        if ($user) { // if authenticated
            // TODO user unnecessary
            $this->user = $user;
            $this->saveUserInsession($user);
        }
    }

    /**
     * Given an array of elements, it checks
     * for specific element.
     * Then it retrieves the fields required
     * for registering of a new User.
     *
     * @param $post: array, copy of $_POST
     * @throws Exception, If some fields are not
     * specified.
     */
    private function validateNewUserFields(array $post)
    {
        if(isset($post['register_name']) && isset($post['register_email'])
            && isset($post['register_password']) && isset($post['register_password_repeat']))
        {
            $this->registerName = $post['register_name'];
            $this->registerEmail = $post['register_email'];

            if ($post['register_password'] == $post['register_password_repeat'])
            {
                $this->registerPassword = $post['register_password'];
                $this->password = $post['register_password_repeat'];
            }
            else // JS should not allow password miss-match
                throw new Exception("Passwords don't match");
        } else {
            throw new Exception("Credential fields are not set!");
        }
    }

    /**
     * If the user returned from web-api call
     * was valid, load it in session and navigate
     * to a successful path, if not (null), navigate
     * to failure path.
     *
     * @param StdClass $user
     * @throws Exception
     */
    private function authenticateUser($user) : void
    {
        if ($user) {
            $this->loadUserInSession($user);
            $this->navigate(true);
        }
        else {
            $this->navigate(false);
        }
    }

    /**
     * Validates the array parameter and
     * if everything ok, it registers
     * new User.
     *
     * @param $post : array, copy of $_POST
     * @throws Exception
     */
    private function validateNewUser(array $post) : void
    {
        $this->validateNewUserFields($post);
        $user = $this->registerUser($this->registerEmail,$this->password, $this->registerName);

        $this->authenticateUser($user);
    }

    /**
     * Validates the array parameter and
     * if everything ok, it authenticates
     * the User.
     *
     * @param $post : array, copy of $_POST
     * @throws Exception
     */
    private function validateExistingUser(array $post) : void
    {
        $this->validateExistingUserFields($post);
        $user = $this->retrieveUser($this->username, $this->password);

        $this->authenticateUser($user);
    }

    /**
     * Given an array of elements, it checks
     * for specific element.
     * Then it retrieves the fields required
     * for authenticating an existing User.
     *
     * @param $post: array, copy of $_POST
     * @throws Exception, If some fields are not
     * specified.
     */
    private function validateExistingUserFields(array $post) : void
    {
        if(isset($post['login_username']) && isset($post['login_password']))
        {
            $this->username = $post['login_username'];
            $this->password = $post['login_password'];
        }
    }

    /**
     * Checks if the submitted form is
     * for existing user.
     *
     * It checks $_GET super global to
     * make sure the form is sent with
     * the right information.
     *
     * @param $get: array. $_GET
     * @return bool, true or false
     * @throws Exception
     */
    private function requestIsFromExistingUserForm(array $get) : bool
    {
        if(!isset($get['id']))
            throw new Exception("Wrong Form Submit!");

        return $get['id'] == "existing_user";
    }

    /**
     * AuthenticateUser constructor.
     * If called from existing user form, then
     * we are dealing with existing user, if not
     * then we are dealing with new user and we
     * need to register it first.
     *
     * @param $get: array, $_GET super-global (copy)
     * @param $post: array, $_POST super-global (copy)
     * @param $http: PhpHttpAdapter object (copy)
     * @throws Exception
     */
    private function __construct(array $get, array $post, PhpHttpAdapter $http, NavigateToLocation $nav)
    {
        if(empty($post) || empty($get) || !isset($http) || !isset($nav))
            throw new Exception("Not valid initialization!");

        $this->http = $http;
        $this->nav = $nav;

        if ($this->requestIsFromExistingUserForm($get))
            $this->validateExistingUser($post);
        else
            $this->validateNewUser($post);
    }

    /**
     * TODO api call
     * @param string $email
     * @param string $password
     * @param string $name
     * @return StdClass
     * @throws Exception
     */
    private function registerUser(string $email, string $password, string $name)
    {
        $this->http->setMethod('POST')
            ->setParameter('some_parameter')
            ->setJsonData(["email" => $email, "name"=> $name, "password" => $password]);

        $this->http->send();
        $user = $this->http->getJsonData();

        return $user;
    }

    /**
     * TODO api call
     * @param $login
     * @param $password
     * @return StdClass
     * @throws Exception
     */
    private function retrieveUser(string $login, string $password)
    {

        $this->http->setMethod('POST')
            ->setParameter('some_parameter')
            ->setJsonData(["login" => $login, "password" => $password]);

        $this->http->send();
        $user = $this->http->getJsonData();

        return $user;
    }

    /**
     * Singleton.
     * @access public
     * @param array $get
     * @param array $post
     * @param PhpHttpAdapter $http
     * @param NavigateToLocation $nav
     * @return AuthenticateUser
     * @throws Exception
     */
    public static function Authenticate(array $get, array $post, PhpHttpAdapter $http, NavigateToLocation $nav) : AuthenticateUser
    {
        if (self::$instance === null) {
            self::$instance = new AuthenticateUser($get, $post, $http, $nav);
        }

        return self::$instance;
    }

    public function __destruct()
    {
        unset($this->failurePath);
        unset($this->password);
        unset($this->registerEmail);
        unset($this->registerName);
        unset($this->registerPassword);
        unset($this->successPath);
        unset($this->username);
        unset($this->http);
        unset($this->nav);
        self::$instance = null;
    }
}