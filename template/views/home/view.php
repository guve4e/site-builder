<?php

require_once (DATA_RESOURCE_PATH . "/User.php");

$u = new User();

try {

    // we need to know who is the user so we can retrieve
    // her information
    $userFromSession = SessionHelper::getUserFromSession();

    // now we can get the right info
    $user = $u->get($userFromSession->hash);

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