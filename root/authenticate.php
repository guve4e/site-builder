<?php
// start session
session_start();

require_once("../relative-paths.php");
require_once (USER_SESSION_PATH . "/AuthenticateUser.php");

try
{
    AuthenticateUser::Authenticate(new PhpHttpAdapter(new RestCall("Curl", new File)), $_POST);

} catch (Exception $e) {

    header("Location: " . "error_500.php");
    die();
}





