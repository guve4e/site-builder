<?php
include (HTTP_PATH . "/jshttp.php");
?>

<script>

    const http = new JSHttp();
    const httpWithRefresh = new JSHttp();

    function foo() {

        let sendData = {key: "value"};

        http
             .setApi("webapi")
             .setMethod("GET")
             .setService("mockcontroller")
             .setParameter(1001)
             .setDataToSend(sendData);

        let response = http.send();

        alert(response)
    }

    function moo() {

        httpWithRefresh
            .setApi("webapi")
            .setMethod("GET")
            .setService("tempsensor")
            .setParameter(1001)
            .setOutputElement(["refreshed_data"])
            .setOutputElementReceivingJsonKey(['temp'])
            .setRefresh(true)
            .setAsync(true)
            .send();
    }

    function bar() {

        new JSHttp()
            .setApi("webapi")
            .setMethod("GET")
            .setService("tempsensor")
            .setParameter(1001)
            .setOutputElement(["current_data"])
            .setOutputElementReceivingJsonKey(['temp'])
            .setTimer(30)
            .setRefresh(true)
            .send();
    }
</script>