<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 3/24/18
 * Time: 10:56 PM
 */

trait SessionHelper
{
    /**
     * Saves user object into $_SESSION
     * super-global.
     */
    protected function saveUserInSession($sessionToken, object $user)
    {
        // save info in session
        $_SESSION[$sessionToken] = $user;
        Logger::logMsg("USER_SESSION", print_r($user, true));
    }
}