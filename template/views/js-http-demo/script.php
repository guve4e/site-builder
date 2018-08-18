<?php
include (HTTP_PATH . "/jshttp.php");
?>

<script>

    function foo() {

        let sendData = {key: "value"};

        const http = new JSHttp()
         //   .isMock(true)
            .setMethod("GET")
            .setService("mockcontroller")
            .setParameter(1001)
            .setDataToSend(sendData);

        let response = http.send();

        alert(response)
    }

    function moo() {
        new JSHttp()
            .setMethod("GET")
            .setService("mockcontroller")
            .setParameter(1001)
            .setOutputElement("refreshed_data")
            .setOutputElementReceivingJsonKey('num')
            .setRefresh(true)
            .setAsync(true)
            .send();
    }

    function bar() {

        new JSHttp()
            .setMethod("GET")
            .setService("mockcontroller")
            .setParameter(1001)
            .setOutputElement("current_data")
            .setOutputElementReceivingJsonKey('num')
            .setTimer(30)
            .setRefresh(true)
            .send();
    }
</script>