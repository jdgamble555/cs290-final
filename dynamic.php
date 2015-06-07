<?php
/*
 * Jonathan Gamble
 */

session_start();

if (empty($_SESSION)) {
    header("Location: login.php");
    die;
}

// check for action
isset($_POST['action']) ? $action = $_POST['action'] : die('No Action');

// db
require 'connect.php';
$terms = new connect('_final_terms');

ini_set('display_errors',1);
ini_set('display_startup_errors',1);
error_reporting(-1);

$formVars = ['term', 'definition', 'id', 'position'];
$delete = '<button class="deleteRow">delete</button>';
$edit = '<button class="editRow">edit</button>';
$data = $ouput = [];

// get data
for ($i = 0; $i < count($formVars); $i++) {
    if (isset($_POST[$formVars[$i]])) {
        $data[$formVars[$i]] = htmlentities($_POST[$formVars[$i]]);
    }
}

$output['data'] = $data;

// add
if ($action === 'add') {
    
    $data['user'] = $_SESSION['user'];
    
    $output['error'] = $terms->add($data);
    // add delete column
    $output['data']['delete'] = $delete;
    $output['data']['edit'] = $edit;
    $output['id'] = $terms->id;
}    
// delete
elseif ($action === 'del' && isset($data['id'])) {
    $output['error'] = $terms->delete($data['id']);
}
// sort
elseif ($action === 'sort' && isset($_POST['items'])) {
    // update each field position
    $pos = 0;
    foreach ($_POST['items'] as $id) {
        $output['error'] = $terms->update($id, array('position' => ++$pos));
        if ($output['error']) break;
    }
}
// update
elseif ($action === 'update' && isset($data['id']) && isset($_POST['fields'])) {
    $output['error'] = $terms->update($data['id'], $_POST['fields']);
}
// invalid input
else {
    $output['error'] = "Invalid Input";
}
// print header and data
header('Content-type: application/json');
echo json_encode($output);