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
        <title>Login</title>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
        <link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/themes/smoothness/jquery-ui.min.css" />
        <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js"></script>
        <link rel="stylesheet" href="style.css?<?php echo time(); ?>" />
        <script src="login.js?<?php echo time(); ?>"></script>
    </head>
    <body>
        <div id="myWrapper">
            <h1>Word Lists: Login</h1>
            <form id="loginForm">
                <fieldset>
                    <legend>Login</legend>
                <p><label for="email">Email:</label>
                    <input name="email" id="email" type="text" /></p>
                <p><label for="password">Password:</label>
                    <input name="password" id="password" type="password" /><div class="error" id="login_msg"></div></p>
                <button id="login">Login</button>
                </fieldset>
            </form>
            <p>Not a member? <a href="join.php">Join</a></p>
        </div>
    </body>
</html>

