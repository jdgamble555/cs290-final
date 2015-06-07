<?php
/*
 * Jonathan Gamble
 * 
 */
    session_start();
    
    if (empty($_SESSION)) {
        header("Location: login.php");
        die;
    }
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <title>Word List</title>
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://necolas.github.io/normalize.css/3.0.2/normalize.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
    <link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/themes/smoothness/jquery-ui.css" />
    <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js"></script>
    <link rel="stylesheet" href="style.css?<?php echo time(); ?>" />
    <script src="canvas.js?<?php echo time(); ?>"></script>
</head>
    <body>
        <div id="myWrapper">
            <h1>Word Lists</h1>
            <form>
                <fieldset>
                    <legend>The Number One Words</legend>
            <table id="myTable" class="ui-corner-all border">
            <thead class="ui-widget-header">
                <tr>
                    <th>#</th>
                    <th>Term</th>
                    <th>Definition</th>
                </tr>
            </thead>
            <tbody class="ui-widget-content">
<?php require 'list.php'; ?>
            </tbody>
        </table>
                    <p>Edit your word list to get your number one word listed here!</p>
            </fieldset>
            </form>
            <p><a href="edit.php">My Word List</a> - <a href="user.php?action=logout">Logout</a></p>
        </div>
    </body>
</html>
