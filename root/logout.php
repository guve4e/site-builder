<?php

require_once ("../relative-paths.php");
require_once (USER_SESSION_PATH . "/SessionHelper.php");

$url_success = 'index.php';
$url_failure = '?page=login';

SessionHelper::logout();

header("Location: " . $url_success);

die();