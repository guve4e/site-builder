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
    </div>

