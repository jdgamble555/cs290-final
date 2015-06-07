<?php
/*
 * Jonathan Gamble
 */

session_start();

if (empty($_SESSION)) {
    header("Location: login.php");
    die;
}
    
require 'connect.php';
$terms = new connect('_final_terms');
    
$error = $terms->record_exists(array('position' => 1));

if ($error) echo "Error connecting to mySQL: $error";

// sort results by 'position'
function cmp($a, $b) { return $a['position'] - $b['position']; }
usort($terms->result, "cmp");

// print them

$i = 1;            
foreach ($terms->result as $item) {
?>
            <tr>
                <td><?php echo $i++; ?></td>
                <td><canvas width="150" height="100"><?php echo $item['term']; ?></canvas></td>
                <td><?php echo $item['definition']; ?></td>
            </tr>
<?php
}