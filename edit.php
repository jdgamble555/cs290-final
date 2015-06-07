<?php
/* 
 * Jonathan Gamble
 */
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <title>My Word List</title>
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://necolas.github.io/normalize.css/3.0.2/normalize.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
    <link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/themes/smoothness/jquery-ui.css" />
    <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js"></script>
    <link rel="stylesheet" href="style.css?<?php echo time(); ?>" />
    <script src="dynamic.js?<?php echo time(); ?>"></script>
</head>
<body>
<div id="myWrapper">
    <h1>Edit your words!</h1>
    <fieldset>
        <legend>My Words</legend>
    <form id="myForm">
        <table id="myTable" class="ui-corner-all border">
            <thead class="ui-widget-header">
                <tr>
                    <th>#</th>
                    <th>Term</th>
                    <th>Definition</th>
                    <th></th>
                    <th></th>
                </tr>
            </thead>
            <tbody class="ui-widget-content">
<?php require 'items.php'; ?>
            </tbody>
            <tfoot class="ui-widget-content">
                <tr>
                    <td></td>
                    <td><input id="term" class="submitField" placeholder="Enter term" type="text" name="term" /></td>
                    <td><input id="definition" class="submitField" placeholder="Enter definition" type="text" name="definition" /></td>
                    <td><button id="addRow">add</button></td>
                    <td></td>
                </tr>
            </tfoot>
        </table>
        <p><strong>Add</strong> your own words, <strong>drag and drop</strong> to put save your favorites in whatever order you like!</p>
        </fieldset>
    </form>
    <p><a href="index.php">The Number One Words</a> - <a href="user.php?action=logout">Logout</a></p>
</div>
</body>
</html>
