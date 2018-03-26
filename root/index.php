<?php

/**
 * Main index file. The base of every page.
 */

session_start(); //start new session

try {



} catch (Exception $e) {
    $msg =  'Caught exception: ' . $e->getMessage() . "\n";
    echo $msg;
    die();
}

?>

<!doctype html>
<!--[if lte IE 9]> <html class="lte-ie9" lang="en"> <![endif]-->
<!--[if gt IE 9]><!--> <html lang="en"> <!--<![endif]-->
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="initial-scale=1.0,maximum-scale=1.0,user-scalable=no">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <!-- Remove Tap Highlight on Windows Phone IE -->
    <meta name="msapplication-tap-highlight" content="no"/>

    <!--[if lte IE 9]>
    <script type="text/javascript" src=""></script>
    <script type="text/javascript" src=""></script>


    <![endif]-->

</head>
<body>

</body>

</html>