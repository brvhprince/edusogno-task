<?php
/**
 * Project: edusogno-task
 * File: ed-admin.php
 * Author: wanpeninsula
 * Organization: Edusogno
 * Author URI: https://www.pennycodes.dev
 * Created: 09/10/2023 at 4:43 pm
 *
 * Copyright (c) 2023 Edusogno. All rights reserved.
 */

require 'core/init.php';

$is_admin = Session::isAdmin();
if ($is_admin) {
    EdusognoApp::setAdmin(Session::getAdmin());
}

if ($is_admin === false && !str_contains($_SERVER['REQUEST_URI'], 'auth')) {
    Routes::redirect();
}

require 'admin-panel/autoload.php';
