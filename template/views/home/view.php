<?php
require_once (HTTP_PATH . "/phphttp/RestCall.php");

try {
    $restCall = new RestCall("Curl", new File());
    $restCall->setUrl("http://webapi.ddns.net/index.php/mockcontroller/1001");
    $restCall->setContentType("application/json");
    $restCall->setMethod("POST");
    $restCall->addBody(["a" => 'b']);
    $restCall->send();
    $responseAsJson = $restCall->getResponseAsJson();
    $responseAsString = $restCall->getResponseAsString();
} catch (Exception $e) {
    echo $e->getMessage();
}

?>

<div>
    <h4 align="center">Home View</h4>
    <div align="center"><?php var_dump($_SESSION) ?></div>
    <br>
    <div align="center"> <?php var_dump($responseAsJson) ?> </div>
</div>
