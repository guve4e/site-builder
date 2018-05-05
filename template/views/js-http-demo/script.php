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

        response = http.send();

        alert(response)
    }

</script>