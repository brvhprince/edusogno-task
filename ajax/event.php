<?php
/**
 * Project: edusogno-task
 * File: event.php
 * Author: wanpeninsula
 * Organization: Edusogno
 * Author URI: https://www.pennycodes.dev
 * Created: 10/10/2023 at 9:27 pm
 *
 * Copyright (c) 2023 Edusogno. All rights reserved.
 */
global $app;
if ($f === 'event' && Session::checkFormToken($hash_id)) {
    if ($s ==='join' && Session::isUser()) {

        $token = !empty($_POST['token']) ? Utils::encode($_POST['token'], 'd') : '';

        if (empty($token)) {
            http_response_code(422);
            echo json_encode(array(
                'status'=>422,
                'message' => 'Invalid event token. Reload page and try again'
            ));
            exit();
        }

        if (empty($_POST['user'])) {
            http_response_code(422);
            echo json_encode(array(
                'status'=>422,
                'message' => 'Invalid user token. Reload page and try again'
            ));
            exit();
        }



        header("Content-type: application/json");

        if ($app->app('event')->addAttendee( $_POST['user'],$token)) {
            http_response_code(200);
            echo json_encode(array('status'=>200,'message'=>'You have successfully joined this event'));
        }
        else {

            http_response_code(417);
            echo json_encode(array('status'=>417,'message'=>'Failed to join event. Try again later'));
        }
        exit();
    }
    if ($s ==='create' && Session::isAdmin()) {

        if (empty($_POST['name'])) {
            http_response_code(422);
            echo json_encode(array(
                'status'=>422,
                'message' => 'Event name is required'
            ));
            exit();
        }

        if (empty($_POST['date'])) {
            http_response_code(422);
            echo json_encode(array(
                'status'=>422,
                'message' => 'Event date is required'
            ));
            exit();
        }

        header("Content-type: application/json");

        if ($app->app('event')->create($_POST)) {
            http_response_code(201);
            echo json_encode(array('status'=>201,'message'=>'Event added successfully'));
        }
        else {

            http_response_code(417);
            echo json_encode(array('status'=>417,'message'=>'Failed to add event. Try again later'));
        }
        exit();
    }
    if ($s ==='update' && Session::isAdmin()) {
        $token = !empty($_POST['token']) ? Utils::encode($_POST['token'], 'd') : '';

        if (empty($token)) {
            http_response_code(422);
            echo json_encode(array(
                'status'=>422,
                'message' => 'Invalid event token. Reload page and try again'
            ));
            exit();
        }

        $_POST['token'] = $token;

        if (empty($_POST['name'])) {
            http_response_code(422);
            echo json_encode(array(
                'status'=>422,
                'message' => 'Event name is required'
            ));
            exit();
        }

        if (empty($_POST['date'])) {
            http_response_code(422);
            echo json_encode(array(
                'status'=>422,
                'message' => 'Event date is required'
            ));
            exit();
        }

        header("Content-type: application/json");

        if ($app->app('event')->update($_POST)) {
            http_response_code(200);
            echo json_encode(array('status'=>200,'message'=>'Event updated successfully'));
        }
        else {

            http_response_code(417);
            echo json_encode(array('status'=>417,'message'=>'Failed to update event. Try again later'));
        }
        exit();
    }
    if ($s ==='remove' && Session::isAdmin()) {
        $token = !empty($_POST['token']) ? Utils::encode($_POST['token'], 'd') : '';

        if (empty($token)) {
            http_response_code(422);
            echo json_encode(array(
                'status'=>422,
                'message' => 'Invalid event token. Reload page and try again'
            ));
            exit();
        }


        header("Content-type: application/json");

        if ($app->app('event')->delete($token)) {
            http_response_code(200);
            echo json_encode(array('status'=>200,'message'=>'Event removed successfully'));
        }
        else {

            http_response_code(417);
            echo json_encode(array('status'=>417,'message'=>'Failed to remove event. Try again later'));
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
