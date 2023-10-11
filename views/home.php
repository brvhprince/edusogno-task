<?php
/**
 * Project: edusogno-task
 * File: home.php
 * Author: wanpeninsula
 * Organization: Edusogno
 * Author URI: https://www.pennycodes.dev
 * Created: 09/10/2023 at 8:45 pm
 *
 * Copyright (c) 2023 Edusogno. All rights reserved.
 */

if (!Session::isUser()) {
    Routes::redirect('login');
}
Routes::$pageData['title'] = "Welcome to Edusogno";
Routes::$pageData['description'] = "This is the home page";
Routes::$pageData['keywords'] = EdusognoApp::config('site_keywords');
Routes::$pageData['page'] = "Home";
Routes::$pageData['content'] = Routes::loadSitePage('home/content');
