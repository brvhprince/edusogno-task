<?php
/**
 * Project: edusogno-task
 * File: login.php
 * Author: wanpeninsula
 * Organization: Edusogno
 * Author URI: https://www.pennycodes.dev
 * Created: 09/10/2023 at 10:38 pm
 *
 * Copyright (c) 2023 Edusogno. All rights reserved.
 */
if ($f === 'login' && Session::checkMainSession($hash_id)) {
    if ($s ==='user') {
        if (Session::isUser()) {
          Session::destroyUserSession();
        }

        if (empty($_POST['email'])) {
            http_response_code(422);
            echo json_encode(array(
                'status'=>422,
                'message' => 'Email Address is required'
            ));
            exit();
        }
        if (!Utils::validateEmail($_POST['email'])) {
            http_response_code(422);
            echo json_encode(array(
                'status'=>422,
                'message' => 'Enter a valid email address'
            ));
            exit();
        }
        if (empty($_POST['password'])) {

            http_response_code(422);
            echo json_encode(array(
                'status' => 422,
                'message' => 'Password is required'
            ));
            exit();
        }
        $rememberMe = !empty($_POST['remember-me']) && $_POST['remember-me'] === 'yes';
        $results = (new User)->login($_POST['email'],$_POST['password'], $rememberMe);

        if (!$results){

            http_response_code(401);
            echo json_encode(array(
                'status'=>401,
                'message' => 'Invalid credentials. Check and retry '
            ));

        }
        else {

            http_response_code(200);
            echo json_encode(array(
                'status'=>200,
                'message' => 'Login successful. Redirecting... '
            ));
        }
        exit();
    }
    if ($s ==='admin') {
        if (Session::isAdmin()) {
          Session::destroyAdminSession();
        }

        if (empty($_POST['email'])) {
            http_response_code(422);
            echo json_encode(array(
                'status'=>422,
                'message' => 'Email Address is required'
            ));
            exit();
        }
        if (!Utils::validateEmail($_POST['email'])) {
            http_response_code(422);
            echo json_encode(array(
                'status'=>422,
                'message' => 'Enter a valid email address'
            ));
            exit();
        }
        if (empty($_POST['password'])) {

            http_response_code(422);
            echo json_encode(array(
                'status' => 422,
                'message' => 'Password is required'
            ));
            exit();
        }
        $rememberMe = !empty($_POST['remember-me']) && $_POST['remember-me'] === 'yes';
        $results = (new Admin)->login($_POST['email'],$_POST['password'], $rememberMe);

        if (!$results){

            http_response_code(401);
            echo json_encode(array(
                'status'=>401,
                'message' => 'Invalid credentials. Check and retry '
            ));

        }
        else {

            http_response_code(200);
            echo json_encode(array(
                'status'=>200,
                'message' => 'Login successful. Redirecting... '
            ));
        }
        exit();
    }

    http_response_code(401);

    echo json_encode(array('status'=>401,'message'=>'Unauthorized'));
}
else {
    http_response_code(419);

    echo json_encode(array('status'=>419,'message'=>'Page expired. Reload and retry'));

}
exit();
