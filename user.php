<?php

$DEBUG = 1;

/* 
 * Jonathan Gamble
 */

// check for logout
if (isset($_GET['action']) && $_GET['action'] === 'logout') {
    $_POST['action'] = 'logout';
}

// check for action
isset($_POST['action']) ? $action = $_POST['action'] : die('No Action');

// debug headers
if ($DEBUG) {
    ini_set('display_errors',1);
    ini_set('display_startup_errors',1);
    error_reporting(-1);
}

$output = [];

switch($action) {
    case 'join':
        $output = register();
        break;
    case 'login':
        $output = login();
        break;
    case 'valid_email':
        $output = valid_email();
        break;
    case 'logout':
        $output = logout();
        break;
    case 'login':
        $output = login();
}
// print header and data
header('Content-type: application/json');
echo json_encode($output);

/*
 * 
 *  FUNCTION SECTION BELOW 
 * 
 */

function register() {
    // signup a user
    
    // db connection
    require 'connect.php';
    $users = new connect('_final_users');
    
    $output = [];
    
    // validate password
    if (!isset($_POST['password']) || !$_POST['password']) {
        // password not entered
        $output['no_password'] = 1;
    }
    else {
        if (strlen($_POST['password']) < 6) {
            $output['short_pass'] = 1;
        }
    }
    if (!isset($_POST['confirm_password']) || !$_POST['confirm_password']) {
        // password confirm not entered
        $output['no_confirm_password'] = 1;
    }
    if (empty($output)) {
        if ($_POST['confirm_password'] === $_POST['password']) {
            // all is well, encrypt password
            $data['password'] = md5(SALT . md5($_POST['password']) . SALT);
        }
        else {
            // passwords don't match
            $output['no_pass_match'] = 1;
        }
    }
    
    // validate email
    if (isset($_POST['email'])) {
        if (preg_match('/^\S+@\S+\.\S+$/', $_POST['email'])) {
            // all is well get email
            $data['email'] = $_POST['email'];
        }
        else {
            // not a valid email address
            $output['invalid_email'] = 1;
        }
    }
    else {
        // no email entered
        $output['no_email'] = 1;
    }
    
    // make sure email DNE
    
    $sql_error = $users->record_exists(array('email' => $_POST['email']));
    
    if ($sql_error) {
        $output['sql_error'] = $sql_error;
    }
    if ($users->result) {
        $output['taken'] = 1;
    }
    else {
        // add to database if no errors
        if (empty($output)) {
            $sql_error = $users->add($data);
            if ($sql_error) {
                $output['sql_error'] = $sql_error;
            }
            if (empty($output)) {
                // success
                $output['success'] = 1;
                
                // create session
                session_start();
                $_SESSION['email'] = $_POST['email'];
                $_SESSION['user'] = $users->id;
            }
        }
    }
    
    return $output;
}

function valid_email() {
    // see if email is already in database
    
    $output = [];
    
    // db connection
    require 'connect.php';
    $users = new connect('_final_users');
    
    $sql_error = $users->record_exists(array('email' => $_POST['email']));
    
    if ($sql_error) {
        $output['sql_error'] = $sql_error;
    }
    
    $output['taken'] = $users->result;
    
    return $output;
}

function login() {
    // login
    
    $output = [];
    
    // db connection
    require 'connect.php';
    $users = new connect('_final_users');
    
    // make sure email exists
    
    $sql_error = $users->record_exists(
        array(
            'email' => $_POST['email'],
            'password' =>  md5(SALT . md5($_POST['password']) . SALT)
        )
    );
    
    if ($sql_error) {
        $output['sql_error'] = $sql_error;
    }
    if ($users->result) {
        $output['success'] = 1;
        
        // session login
        session_start();
        $_SESSION['email'] = $_POST['email'];
        foreach ($users->result as $u) {
            $_SESSION['user'] = $u['id'];
        }
    }
    else {
        // possibly add check if username exists here
        $output['no_password'] = 1;
    }
    
    return $output;    
}

function logout() {
    // logout
    
    session_start();
    unset($_SESSION);
    session_destroy();
    header("Location: login.php");
    die;
}




