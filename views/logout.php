<?php
/**
 * Project: edusogno-task
 * File: logout.php
 * Author: wanpeninsula
 * Organization: Edusogno
 * Author URI: https://www.pennycodes.dev
 * Created: 10/10/2023 at 2:47 pm
 *
 * Copyright (c) 2023 Edusogno. All rights reserved.
 */

if (!Session::isUser()) {

    Routes::redirect();

}

$IToken = $_GET['token'] ?? '';
if (empty($IToken)) {
    Routes::redirect();
}
$tokenCookieName = Utils::encode("userToken");
$IDCookieName = Utils::encode("userID");

if (Session::exists('user_id') && Session::exists('user_token')) {

    $token = Session::get('user_token');

}elseif (Cookie::exists($tokenCookieName) && Cookie::exists($IDCookieName)) {

    $decryptToken = Cookie::get($tokenCookieName);

    $token = Utils::encode($decryptToken,'d');

}else{

    $token = "Invalid";
}


if(hash_equals($token, $IToken)) {

    Session::destroyUserSession();

}
Routes::redirect();
