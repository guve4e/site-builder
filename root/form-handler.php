<?php
session_start();

require_once ("../relative-paths.php");
require_once (UTILITY_PATH . "/FileManager.php");
require_once (LIBRARY_PATH . "/form/FormExtractor.php");

try {
    new FormExtractor(new FileManager(), $_GET, $_POST, $_FILES);
} catch (Exception $e) {
    // TODO User should not know about that!
    // User should not continue
    die($e->getMessage());
}