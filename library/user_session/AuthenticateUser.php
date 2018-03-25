<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 3/24/18
 * Time: 10:32 PM
 */

require_once("SessionHelper.php");

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
     * @var null
     * Instance variable
     * used to simulate Singleton.
     */
    static private $instance = null;

    /**
     * Given user object, it decides if it is valid
     * or not.
     * Stores the object in session and
     * then it navigates to fail or success
     * place.
     *
     * @param $user: object, representing User.
     * Retrieved form back-end services.
     */
    private function loadUser($user)
    {
        // check for success
        if ($user) { // if authenticated
            //ob_end_clean();

            $this->user = $user;
            $this->saveUserInsession("authenticated_user");

            header("Location: " . $this->successPath);
        } else {    // if NOT authenticated
            header("Location: " . $this->failurePath . "?error=authorized");
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
            else
                throw new Exception("Passwords don't match");
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
    private function validateNewUser(array $post)
    {
        $this->validateNewUserFields($post);
        $user = $this->registerUser($this->registerEmail,$this->password, $this->registerName);

        $this->loadUser($user);
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
    private function validateExistingUserFields(array $post)
    {
        if(isset($post['login_username']) && isset($post['login_password']))
        {
            $this->username = $post['login_username'];
            $this->password = $post['login_password'];
        }
    }

    /**
     * Validates the array parameter and
     * if everything ok, it authenticates
     * the User.
     *
     * @param $post : array, copy of $_POST
     * @throws Exception
     */
    private function validateExistingUser(array $post)
    {
        $this->validateExistingUserFields($post);
        $user = $this->retrieveUser($this->username, $this->password);

        $this->loadUser($user);
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
    private function isExistingUser(array $get)
    {
        if(!isset($get['id']))
            throw new Exception("Wrong Form Submit!");

        return $get['id'] == "existing_user";
    }

    /**
     * AuthenticateUser constructor.
     *
     * @param $get: array, $_GET super-global (copy)
     * @param $post: array, $_POST super-global (copy)
     * @param $http: PhpHttpAdapter object (copy)
     * @throws Exception
     */
    private function __construct(array $get, array $post, PhpHttpAdapter $http)
    {
        if(!is_array($post) || !is_array($get))
            throw new Exception("Not valid initialization!");

        if ($this->isExistingUser($get))
            $this->validateExistingUser($post);
        else
            $this->validateNewUser($post);
    }

    /**
     * TODO api call
     * @param $email
     * @param $password
     * @param $name
     * @return StdClass
     */
    public function registerUser(string $email, string $password, string $name)
    {
        $tmp = new StdClass;
        $tmp->U_ID = 10004;
        $tmp->PRODUCTS_COUNT = 455;
        return $tmp;
    }

    /**
     * TODO api call
     * @param $login
     * @param $password
     * @return StdClass
     */
    public function retrieveUser(string $login, string $password)
    {
        $tmp = new StdClass;
        $tmp->U_ID = 10004;
        $tmp->PRODUCTS_COUNT = 455;

        return $tmp;
    }

    /**
     * Singleton.
     * @access public
     * @return AuthenticateUser
     * @throws Exception
     */
    public static function Authenticate(array $get, array $post, PhpHttpAdapter $http) : AuthenticateUser
    {
        if (self::$instance === null) {
            self::$instance = new AuthenticateUser($get, $post, $http);
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
        self::$instance = null;
    }
}