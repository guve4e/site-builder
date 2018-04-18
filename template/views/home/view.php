<?php
require_once (DATA_RESOURCE_PATH . "/User.php");

function getUserFromSession()
{
    $user = null;
    if(isset($_SESSION["some_website_user"]))
        $user = $_SESSION["some_website_user"];
    else
        throw new Exception("User is not SET!");

    return $user;
}

try {
    $userFromSession = $this->getUserFromSession();
} catch (Exception $e) {
    die($e->getMessage());
}

$u = new User();
try {
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