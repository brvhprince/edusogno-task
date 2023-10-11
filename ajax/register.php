<?php
/**
 * Project: edusogno-task
 * File: register.php
 * Author: wanpeninsula
 * Organization: Edusogno
 * Author URI: https://www.pennycodes.dev
 * Created: 10/10/2023 at 12:31 pm
 *
 * Copyright (c) 2023 Edusogno. All rights reserved.
 */

if ($f === 'register' && Session::checkMainSession($hash_id)) {
    if ($s ==='user') {
        if (Session::isUser()) {
            Session::destroyUserSession();
        }

        $userController = new User();
        if (empty($_POST['first_name'])) {
            http_response_code(422);
            echo json_encode(array(
                'status'=>422,
                'message' => 'First Name is required'
            ));
            exit();
        }

        if (empty($_POST['last_name'])) {
            http_response_code(422);
            echo json_encode(array(
                'status'=>422,
                'message' => 'First Name is required'
            ));
            exit();
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

        if ($userController->emailExists($_POST['email'])) {
            http_response_code(422);
            echo json_encode(array(
                'status'=>422,
                'message' => 'Email address belongs to another user'
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

        if (EdusognoApp::config('strong_password') == "1") {
            if (Utils::passwordStrength($_POST['password'])) {
                http_response_code(422);
                echo json_encode(array(
                    'status' => 422,
                    'message' => 'Password should be at least 6 characters long and should contain at least 1 letter and 1 number or 1 special character'
                ));
                exit();
            }
        }

        if (empty($_POST['confirm_password'])) {
            http_response_code(422);
            echo json_encode(array(
                'status'=>422,
                'message' => 'Password confirmation is required'
            ));
            exit();
        }



        if ($_POST['password'] !== $_POST['confirm_password']) {
            http_response_code(422);
            echo json_encode(array(
                'status'=>422,
                'message' => 'Password and password confirmation do not match'
            ));
            exit();
        }

        header("Content-type: application/json");

        if ($userController->create($_POST)) {
            http_response_code(201);
            echo json_encode(array('status'=>201,'message'=>'Account created successfully'));
        }
        else {

            http_response_code(417);
            echo json_encode(array('status'=>417,'message'=>'An error occurred creating user account. retry'));
        }
        exit();
    }
    if ($s ==='admin') {
        if (!empty($_SESSION['admin_login_status'])) {
            $_SESSION['admin_login_status'] = $_SESSION['admin_token'] = $_SESSION['adminID'] = '';

            unset($_SESSION['admin_login_status']);
            unset($_SESSION['admin_token']);
            unset($_SESSION['adminID']);
        }
        $IdCookieName = encode("adminID");
        $tokenCookieName = encode("adminToken");
        if (!empty($_COOKIE[$IdCookieName])) {
            $_COOKIE[$IdCookieName] =  $_COOKIE[$tokenCookieName] = '';
            unset($_COOKIE[$tokenCookieName]);
            unset($_COOKIE[$IdCookieName]);
            setcookie($IdCookieName, "", -1);
            setcookie($IdCookieName, "", -1,'/');
            setcookie($tokenCookieName, "", -1);
            setcookie($tokenCookieName, "", -1,'/');
        }

        if (empty($_POST['email'])) {
            http_response_code(422);
            echo json_encode(array(
                'status'=>422,
                'message' => 'Email Address is required'
            ));
            exit();
        }
        if (!validateEmail($_POST['email'])) {
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
        $results = Ex_AdminLogin($_POST['email'],$_POST['password']);

        if ($results == null){

            http_response_code(401);
            echo json_encode(array(
                'status'=>401,
                'message' => 'Invalid credentials. Check and retry '
            ));

        }
        else {

            $adminToken = bin2hex(openssl_random_pseudo_bytes(24));
            $_SESSION['admin_login_status'] = true;
            $_SESSION['adminID'] = encode($results['admin_id']);
            $_SESSION['admin_token'] = $adminToken;
            if ((!empty($_POST['remember-me'])) && $_POST['remember-me'] === 'yes' ) {
                $expireTime = time() + 86400;
                $tokenName = $_SESSION['admin_token'];
                $adminID = $_SESSION['adminID'];
                $token_encryption = encode($tokenName);
                $id_encryption = encode($adminID);

                $tokenCookieName = encode("admin_token");
                $IdCookieName = encode("adminID");
                setcookie($tokenCookieName, $token_encryption, $expireTime, "/");
                setcookie($IdCookieName, $id_encryption, $expireTime, "/");
            }
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
