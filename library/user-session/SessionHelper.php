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

trait SessionHelper
{
    /**
     * Saves user object into $_SESSION
     * super-global.
     * @param object $user
     * @throws Exception
     */
    protected function saveUserInSession(object $user)
    {
        $jsonLoader = new SiteConfigurationLoader(new File());
        $configuration = $jsonLoader->getData();
        $sessionToken = $configuration->session->key;

        // save info in session
        $_SESSION[$sessionToken] = $user;
        $logger = new Logger(new File());
        $logger->logMessage("User Session: ", print_r($user, true));
    }
}