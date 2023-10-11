<?php
/**
 * Project: edusogno-task
 * File: ed-home.php
 * Author: wanpeninsula
 * Organization: Edusogno
 * Author URI: https://www.pennycodes.dev
 * Created: 09/10/2023 at 4:43 pm
 *
 * Copyright (c) 2023 Edusogno. All rights reserved.
 */

require_once 'core/init.php';

$page = $_GET['page'] ?? 'login';

if (Session::isUser()) {
    EdusognoApp::setUser(Session::getUser());
    if ($page === 'login') {
        $page = 'home';
    }
}

switch ($page){
    case 'login':
        include ('views/login.php');
        break;
    case 'home':
        include ('views/home.php');
        break;
    case 'register':
        include ('views/register.php');
        break;
    case 'logout':
        include ('views/logout.php');
        break;
    case 'forgot-password':
        include ('views/forgot-password.php');
        break;
    case 'reset-password':
        include ('views/reset-password.php');
        break;
    default;
        include "views/404.php";
        break;
};

echo Routes::loadSitePage('container');
Database::close();
