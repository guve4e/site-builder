<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 3/24/18
 * Time: 10:56 PM
 */

require_once (UTILITY_PATH . "/Logger.php");
require_once (UTILITY_PATH . "/File.php");

trait SessionHelper
{
    /**
     * Saves user object into $_SESSION
     * super-global.
     * @throws Exception
     */
    protected function saveUserInSession($sessionToken, object $user)
    {
        // save info in session
        $_SESSION[$sessionToken] = $user;
        $logger = new Logger(new File());
        $logger->logMessage("User Session: ", print_r($user, true));
    }
}