<?php
/**
 * Project: edusogno-task
 * File: system.php
 * Author: wanpeninsula
 * Organization: Edusogno
 * Author URI: https://www.pennycodes.dev
 * Created: 11/10/2023 at 1:24 am
 *
 * Copyright (c) 2023 Edusogno. All rights reserved.
 */
global $app;

if ($f === 'system' && Session::isAdmin()) {
    if ($s ==='configuration' && Session::checkFormToken($hash_id)) {
        $saveSetting = false;
        $skip = ['hash_id'];

        foreach ($_POST as $key => $value) {
            if (!in_array($key,$skip)) {
                $saveSetting = $app->saveConfig($key, $value);
            }
        }
        header("Content-type: application/json");
        if ($saveSetting) {
            http_response_code(200);
            echo json_encode(array('status'=>200,'message'=>'Configurations saved successfully'));
        } else {

            http_response_code(417);
            echo json_encode(array('status'=>417,'message'=>'Error: An error occurred updating configurations'));
        }
        exit();
    }


    header("Content-type: application/json");
    http_response_code(400);
    echo json_encode(array('status'=>400,'message'=>'Session expired. Reload page and retry'));
}
else {
    http_response_code(401);

    echo json_encode(array('status'=>401,'message'=>'Unauthorized'));
}
exit();
