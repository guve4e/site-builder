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
require_once (LIBRARY_PATH. "/configuration/SiteConfiguration.php");

trait SessionHelper
{
    /**
     * Saves user object into $_SESSION
     * super-global.
     * @param $sessionToken
     * @param object $user
     * @throws Exception
     */
    protected function saveUserInSession(object $user)
    {
        // make session with the user
        $json = new JsonLoader(new File(),SITE_CONFIGURATION_PATH);
        $configuration = SiteConfiguration::LoadSiteConfiguration($json);
        $sessionToken = $configuration->session->key;

        // save info in session
        $_SESSION[$sessionToken] = $user;
        $logger = new Logger(new File());
        $logger->logMessage("User Session: ", print_r($user, true));
    }
}