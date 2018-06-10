<?php
include (HTTP_PATH . "/jshttp.php");
?>

<script>

    function foo() {

        let sendData = {key: "value"};

        const http = new JSHttp()
            .isMock(true)
            .setMethod("GET")
            .setService("user")
            .setDataToSend(sendData);

        let response = http.send();

        alert(response)
    }

    function bar() {

        dataDiv = document.getElementById('ajax_button2');

        const http = new JSHttp()
            .setMethod("GET")
            .setService("mockcontroller")
            .setParameter(1001)
            .setElement("current_data")
            .setTimer(60)
            .send();
    }
</script>