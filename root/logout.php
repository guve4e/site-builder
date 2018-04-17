<?php
$url_success = 'index.php';
$url_failure = 'login.php';

Session::logout();

header("Location: " . $url_success);

die();