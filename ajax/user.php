<?php
/**
 * Project: edusogno-task
 * File: user.php
 * Author: wanpeninsula
 * Organization: Edusogno
 * Author URI: https://www.pennycodes.dev
 * Created: 11/10/2023 at 12:47 am
 *
 * Copyright (c) 2023 Edusogno. All rights reserved.
 */
global $app;
if ($f === 'user' && Session::checkFormToken($hash_id)) {
    if ($s ==='remove' && Session::isAdmin()) {
        $token = !empty($_POST['token']) ? Utils::encode($_POST['token'], 'd') : '';

        if (empty($token)) {
            http_response_code(422);
            echo json_encode(array(
                'status'=>422,
                'message' => 'Invalid user token. Reload page and try again'
            ));
            exit();
        }


        header("Content-type: application/json");

        if ($app->app('user')->delete($token)) {
            http_response_code(200);
            echo json_encode(array('status'=>200,'message'=>'User removed successfully'));
        }
        else {

            http_response_code(417);
            echo json_encode(array('status'=>417,'message'=>'Failed to remove user. Try again later'));
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
