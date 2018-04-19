<?php

// UPDATE INFO
$loginForm = new FormHandler();
try {
    $loginForm->navigateTo("form-handler")
        ->setEntity("User")
        ->setVerb("authenticate")
        ->setNavigateAfterUpdate(true)
        ->setPathSuccess("home")
        ->setPathFail("login");
} catch (Exception $e) {

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

    <title> Login Page</title>

</head>
<body>
<div>
    <div id="login_card" align="center">
        <div id="login_form">
            <form method="post" action="
            <?php try {
                $loginForm->printFormAction();
            } catch (Exception $e) {
                // Choose what to do here
            } ?>">
                <br>
                <div >
                    <label for="login_username">Username</label>
                    <input type="text" id="login_username" name="login_username" />
                </div>
                <br>
                <div >
                    <label for="login_password">Password</label>
                    <input type="password" id="login_password" name="login_password" />
                </div>
                <br>
                <div>
                    <button type="submit">Sign In</button>
                </div>
            </form>
        </div>

        <div>
            <a href="#" id="signup_form_show">Create an account</a>
            <br>
        </div>
        <div>
            <a href="logout.php" >Close</a>
        </div>
    </div>
</body>
