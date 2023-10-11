<?php
/**
 * Project: edusogno-task
 * File: password.php
 * Author: wanpeninsula
 * Organization: Edusogno
 * Author URI: https://www.pennycodes.dev
 * Created: 10/10/2023 at 7:02 pm
 *
 * Copyright (c) 2023 Edusogno. All rights reserved.
 */

if ($f === 'password' && Session::checkMainSession($hash_id)) {
    if ($s ==='forgot') {

        $userController = new User();

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

        if (!$userController->emailExists($_POST['email'])) {
            http_response_code(422);
            echo json_encode(array(
                'status'=>422,
                'message' => 'No user was found with this email address'
            ));
            exit();
        }

        if (Helpers::hasPendingVerificationCode($_POST['email'])) {
            http_response_code(422);
            echo json_encode(array(
                'status'=>422,
                'message' => 'A password reset link has already been sent to this email address. Check your inbox or spam folder'
            ));
            exit();
        }


        header("Content-type: application/json");

        if (Helpers::sendPasswordResetInstructions($_POST['email'])) {
            http_response_code(200);
            echo json_encode(array('status'=>200,'message'=>'Password reset link has been sent to your email address'));
        }
        else {

            http_response_code(417);
            echo json_encode(array('status'=>417,'message'=>'Password reset link could not be sent. Try again later'));
        }
        exit();
    }
    if ($s ==='reset') {

        $userController = new User();


        if (empty($_POST['code'])) {
            http_response_code(422);
            echo json_encode(array(
                'status'=>422,
                'message' => 'Enter verification code'
            ));
            exit();
        }

        if (empty($_POST['new_password'])) {
            http_response_code(422);
            echo json_encode(array(
                'status'=>422,
                'message' => 'Enter new password'
            ));
            exit();
        }
        if (empty($_POST['verify_password'])) {
            http_response_code(422);
            echo json_encode(array(
                'status'=>422,
                'message' => 'Enter password confirmation'
            ));
            exit();
        }


        if (EdusognoApp::config('strong_password') == "1") {
            if (Utils::passwordStrength($_POST['new_password'])) {
                http_response_code(422);
                echo json_encode(array(
                    'status' => 422,
                    'message' => 'Password should be at least 6 characters long and should contain at least 1 letter and 1 number or 1 special character'
                ));
                exit();
            }
        }


        if ($_POST['new_password'] !== $_POST['verify_password']) {
            http_response_code(422);
            echo json_encode(array(
                'status'=>422,
                'message' => 'Passwords do not match'
            ));
            exit();
        }

        $email = Helpers::isValidVerificationCode($_POST['code']);

        if (!$email) {
            http_response_code(422);
            echo json_encode(array(
                'status'=>422,
                'message' => 'Verification code is invalid or has expired'
            ));
            exit();
        }

        header("Content-type: application/json");

        if ($userController->updatePassword($_POST['new_password'], $email)) {
            http_response_code(200);
            echo json_encode(array('status'=>200,'message'=>'Password reset successful. You can now login with your new password'));
        }
        else {

            http_response_code(417);
            echo json_encode(array('status'=>417,'message'=>'Password reset failed. Try again later'));
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
