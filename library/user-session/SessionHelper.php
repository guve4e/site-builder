<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 3/24/18
 * Time: 10:56 PM
 */

require_once (UTILITY_PATH . "/Logger.php");
require_once (UTILITY_PATH . "/File.php");
require_once (UTILITY_PATH . "/JsonLoader.php");
require_once (CONFIGURATION_PATH. "/SiteConfigurationLoader.php");

class SessionHelper
{
    /**
     * @throws Exception
     */
    private static function getSessionToken()
    {
        $jsonLoader = new SiteConfigurationLoader(new File());
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
        return new Logger(new File());
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
        $logger->logMessage("User Session: ", print_r($user, true));
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

}