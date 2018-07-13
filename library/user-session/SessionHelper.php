<?php

require_once (UTILITY_PATH . "/Logger.php");
require_once(UTILITY_PATH . "/FileManager.php");
require_once (UTILITY_PATH . "/JsonLoader.php");
require_once (CONFIGURATION_PATH. "/SiteConfigurationLoader.php");


class SessionHelper
{
    /**
     * @throws Exception
     */
    private static function getSessionToken()
    {
        $jsonLoader = new SiteConfigurationLoader(new FileManager());
        $configuration = $jsonLoader->getData();
        $sessionToken = $configuration->session->key;

        return $sessionToken;
    }

    /**
     * @return Logger
     * @throws Exception
     */
    private static function getLogger()
    {
        return new Logger(new FileManager());
    }

    /**
     * Saves user object into $_SESSION
     * super-global.
     * @param object $user
     * @throws Exception
     */
    public static function saveUserInSession(stdClass $user)
    {

        $sessionToken = self::getSessionToken();
        // save info in session
        $_SESSION[$sessionToken] = $user;

        $logger = self::getLogger();
        $logger->logMessage("User Get: ", print_r($user, true));
    }

    /**
     * @return null
     * @throws Exception
     */
    public static function getUserFromSession()
    {
        $sessionToken = self::getSessionToken();

        $user = null;
        if(isset($_SESSION[$sessionToken]))
            $user = $_SESSION[$sessionToken];
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
}