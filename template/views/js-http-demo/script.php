<?php
include (HTTP_PATH . "/jshttp.php");
?>

<script>

    function foo() {

        let sendData = {key: "value"};


         //http.isMock(true)
        http.setMethod("GET")
        http.setService("mockcontroller")
        http.setParameter(1001)
        http.setDataToSend(sendData);

        let response = http.send();

        alert(response)
    }

    function moo() {

        http.setMethod("GET")
        http.setService("tempsensor")
        http.setParameter(1001)
        http.setOutputElements(["refreshed_data"])
        http.setOutputElementReceivingJsonKeys(['temp'])
        http.setRefresh(true)
        http.setAsync(true)
        http.send();
    }

    function bar() {


        http.setMethod("GET")
        http.setService("tempsensor")
        http.setParameter(1001)
        http.setOutputElements(["current_data"])
        http.setOutputElementReceivingJsonKeys(['temp'])
        http.setTimer(30)
        http.setRefresh(true)
        http.send();
    }
</script>