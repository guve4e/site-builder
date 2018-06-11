<?php
    $user = new StdClass;
    $user->U_ID = 1;
?>

    <div align="center">
        <h4>JS HTTP request Demo</h4>
            <button onclick="foo()" id="ajax_button1">JS HTTP Request</button>
        <br>
    </div>
    <br>
    <br>
    <div align="center">
        <button onclick="moo()" id="ajax_button2">JS HTTP Request with refresh</button>
    </div>
    <br>
    <br>
    <div id="refreshed_data" align="center">
        <p>Expected Refreshed Value...</p>
    </div>
    <br>
    <br>
    <div align="center">
        <button onclick="bar()" id="ajax_button3">JS HTTP Request with timer</button>
    </div>

    <br>
    <br>
    <div id="current_data" align="center">
        <p>Expected Timer Value...</p>
    </div>


