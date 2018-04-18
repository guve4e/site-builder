<?php

require_once ("../relative-paths.php");
require_once (LIBRARY_PATH . "/user-session/UserSession.php");

$url_success = 'index.php';
$url_failure = '?page=login';

UserSession::logout();

header("Location: " . $url_success);

die();