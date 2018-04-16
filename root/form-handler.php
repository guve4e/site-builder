<?php
session_start();

require_once ("../relative-paths.php");
require_once (LIBRARY_PATH . "/form/FormHandler.php");

try {
    new FormHandler($_GET, $_POST);
} catch (Exception $e) {
    // TODO User should not know about that!
    // User should not continue
    die($e->getMessage());
}