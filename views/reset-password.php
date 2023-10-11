<?php
/**
 * Project: edusogno-task
 * File: reset-password.php
 * Author: wanpeninsula
 * Organization: Edusogno
 * Author URI: https://www.pennycodes.dev
 * Created: 10/10/2023 at 8:14 pm
 *
 * Copyright (c) 2023 Edusogno. All rights reserved.
 */

if (empty($_GET['code'])) {
    Routes::redirect('login');
}

Routes::$pageData['title'] = "Reset Your Password for Edusogno";
Routes::$pageData['description'] = "This is the reset password page";
Routes::$pageData['keywords'] = EdusognoApp::config('site_keywords');
Routes::$pageData['page'] = "Reset Password";
Routes::$pageData['content'] = Routes::loadSitePage('reset-password/content');
