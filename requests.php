<?php
/**
 * Project: edusogno-task
 * File: requests.php
 * Author: wanpeninsula
 * Organization: Edusogno
 * Author URI: https://www.pennycodes.dev
 * Created: 09/10/2023 at 10:35 pm
 *
 * Copyright (c) 2023 Edusogno. All rights reserved.
 */

require_once('core/init.php');

$f = '';
$s = '';
if (isset($_GET['f'])) {
    $f = Utils::secureInput($_GET['f'], 0);
}
if (isset($_GET['s'])) {
    $s =  Utils::secureInput($_GET['s'], 0);
}
$hash_id = '';
$errors = [];
if (!empty($_POST['hash_id'])) {
    $hash_id = $_POST['hash_id'];
} else if (!empty($_GET['hash_id'])) {
    $hash_id = $_GET['hash_id'];
} else if (!empty($_GET['hash'])) {
    $hash_id = $_GET['hash'];
} else if (!empty($_POST['hash'])) {
    $hash_id = $_POST['hash'];
}
$data            = [];

$allow_array     = array(
    'system',
    'register',
    'password',
    'event',
    'user',
    'login'
);
$non_login_array = array(
    'login',
    'register',
    'password',
);

if (!in_array($f, $allow_array)) {
    header("Content-type: application/json");
    http_response_code(417);
    echo json_encode(array('status' => 417, 'message' => 'Error: Looks like you\'re lost'));
    exit();
}
if (!empty($_SERVER['HTTP_X_REQUESTED_WITH'])) {
    if (strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest') {
        header("Content-type: application/json");
        http_response_code(417);
        echo json_encode(array('status' => 417, 'message' => 'Error: Looks like you\'re lost'));
        exit();
    }
} else {
    header("Content-type: application/json");
    http_response_code(417);
    echo json_encode(array('status' => 417, 'message' => 'Error: Looks like you\'re lost'));
    exit();
}
if (!in_array($f, $non_login_array)) {

    if (!Session::isUser() && !Session::isAdmin()) {
        header("Content-type: application/json");
        http_response_code(417);
        echo json_encode(array('status'=>417,'message'=>"Error: You are lost and that is all we know"));
        sleep(1);
        Routes::redirect('login');
    }
}

$files = scandir('ajax');
unset($files[0]);
unset($files[1]);
if (file_exists('ajax/' . $f . '.php') && in_array($f . '.php', $files)) {

    include 'ajax/' . $f . '.php';
}
Database::close();
exit();
