<?php

/**
 * The base of every page.
 */

session_start();

require_once ("../relative-paths.php");
require_once (CONFIGURATION_PATH . "/SiteConfigurationLoader.php");
require_once (FORM_HANDLER_PATH . "/FormHandler.php");
require_once (USER_SESSION_PATH . "/Chrono.php");
require_once (HTTP_PATH . "/PhpHttpAdapter.php");
require_once (BUILD_PATH . "/PageBuilder.php");
require_once (BUILD_PATH . "/PageDirector.php");


try {

    Chrono::checkTimer();

    $pageBuilder = new PageBuilder();
    $pageDirector = new PageDirector($pageBuilder);
    $pageDirector->buildPage();

    Chrono::startTimer();

} catch (Exception $e) {
    die('Caught exception: ' . $e->getMessage() . "\n");
}

