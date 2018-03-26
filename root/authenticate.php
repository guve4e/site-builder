<?php
// start session
session_start();

try
{
    AuthenticateUser::Authenticate($_GET, $_POST);

} catch (Exception $e) {

    header("Location: " . "error_500.php");
    die();
}





