<?php

/**
 * The base of every page.
 */

session_start();

require_once ("../config.php");
require_once (USER_SESSION_PATH . "/Chrono.php");
require_once (BUILD_PATH . "/PageBuilder.php");

Chrono::checkTimer();

try {
    // construct the page
    $site = PageBuilder::MakePage(new File(), $_GET);

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

    <!--*** Set Title ***-->
    <title><?php $site->printTitle(); ?></title>

    <?php try {
        $site->printStyles();
    } catch (Exception $e) {
    } ?>

    <!--[if lte IE 9]>
    <script type="text/javascript" src=""></script>
    <script type="text/javascript" src=""></script>


    <![endif]-->

</head>
    <body>
    <?php
    /** construct the main page **/

    try {

        $site->build();
        $site->printScripts();
        $site->loadJavaScript();

        Chrono::startTimer();

    } catch (Exception $e) {
        // something went wrong
        // user should not continue
        // if page is not constructed
        $msg =  'Caught exception: ' . $e->getMessage() . "\n";
        echo $msg;
        die();
    }

    ?>
    </body>

</html>