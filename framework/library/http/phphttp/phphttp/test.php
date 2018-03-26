<?php
/**
 * Created by PhpStorm.
 * User: home
 * Date: 1/20/18
 * Time: 11:35 AM
 */

    require_once("RestCall.php");


    $r = new RestCall("Curl");
    $r->setUrl("http://crystalpure.ddns.net/web-api/index.php/mockcontroller/1001");
    $r->setContentType("application/json");
    $r->setMethod("POST");
    $r->setJsonData(["a"=>'b']);
    $a = $r->send();
    var_dump($a->getBody());