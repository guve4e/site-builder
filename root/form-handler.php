<?php
session_start();

require_once ("../relative-paths.php");
require_once(LIBRARY_PATH . "/form/FormExtractor.php");

try {
    new FormExtractor($_GET, $_POST);
} catch (Exception $e) {
    // TODO User should not know about that!
    // User should not continue
    die($e->getMessage());
}