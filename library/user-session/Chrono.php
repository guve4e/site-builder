<?php

/**
 * Static class, that acts as
 * chronometer. It takes time.
 * checkTime() is called in index.php
 * at the beginning of each page.
 * It checks time and compares it to
 * time expiration value contained in
 * $_SESSION super-global.
 * startTime() is called at the end of each
 * index.php, to start new timer.
 * If the user does not click anywhere on the
 * page and the time expires, she is prompt to
 * login or to continue logged out as a guest.
 */
class Chrono
{
    /**
     * @var integer
     * Represents time
     * interval in minutes.
     */
    private static $timeInterval = 1;

    /**
     * Starts timer.
     * Referenced from index.php in the beginning.
     *
     */
    public static function startTimer()
    {
        if (isset($_SESSION['authenticated_user']))
        {
            $timeNow = time();

            // set expiration time
            $_SESSION['time_expiration'] = $timeNow + (self::$timeInterval * 60);
        }
    }

    /**
     * Checks if session is expired
     * If yes, destroys session and navigates
     * to login.php
     * Referenced from index.php at the beginning.
     */
    public static function checkTimer()
    {
        // make sure the SESSION is not expired
        if (isset($_SESSION['authenticated_user']) && isset($_SESSION['time_expiration']) )
        {
            // check time
            $time_now = time();

            if ($time_now > $_SESSION['time_expiration'])
            {
                session_destroy();
                header("Location: ?page=login");
            }
        }
    }
}