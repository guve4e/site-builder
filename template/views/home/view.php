<?php
require_once (DATA_RESOURCE_PATH . "/User.php");

try {
    $userFromSession = UserSession::getUserFromSession();
} catch (Exception $e) {
    die($e->getMessage());
}

$u = new User();
try {
    $user = $u->get($userFromSession->id);
} catch (Exception $e) {
    die($e->getMessage());
}

?>

<div>
    <h4 align="center">Home View</h4>
    <div align="center"><?php var_dump($_SESSION) ?></div>
    <br>
    <div align="center"> <?php var_dump($user) ?> </div>
</div>