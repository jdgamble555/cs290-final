<?php
/*
 * Jonathan Gamble
 */
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width">
        <title>Join</title>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
        <link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/themes/smoothness/jquery-ui.min.css" />
        <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js"></script>
        <link rel="stylesheet" href="style.css?<?php echo time(); ?>" />
        <script src="join.js?<?php echo time(); ?>"></script>
    </head>
    <body>
        <div id="myWrapper">
            <h1>Word Lists: Join Our Site</h1>
            <form id="joinForm">
                <fieldset>
                    <legend>Register</legend>
                <p><label for="email">Email:</label>
                <input name="email" id="email" type="text" /><div class="error" id="email_msg"></div></p>
                <p><label for="password">Password:</label>
                    <input name="password" id="password" type="password" /><div class="error" id="pass_msg"></div></p>
                <p><label for="confirm_password">Confirm Password:</label>
                    <input name="confirm_password" id="confirm_password" type="password" /><div class="error" id="confirm_pass_msg"></div></p>
                <button id="join">Join</button>
                </fieldset>
            </form>
            <p>Already a member? <a href="login.php">Login</a></p>
        </div>
    </body>
</html>
