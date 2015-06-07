<?php

session_start();

if (empty($_SESSION)) {
    header("Location: login.php");
    die;
}
    
require 'connect.php';
$terms = new connect('_final_terms');
    
$error = $terms->record_exists(array('user' => $_SESSION['user']));

if ($error) echo "Error connecting to mySQL: $error";

// sort results by 'position'
function cmp($a, $b) { return $a['position'] - $b['position']; }
usort($terms->result, "cmp");

// print them

$i = 1;            
foreach ($terms->result as $item) {
?>
            <tr id="<?php echo $item['id']; ?>">
                <td><?php echo $i++; ?></td>
                <td><?php echo $item['term']; ?></td>
                <td><?php echo $item['definition']; ?></td>
                <td><button class="deleteRow">delete</button></td>
                <td><button class="editRow">edit</button></td>
            </tr>
<?php
}