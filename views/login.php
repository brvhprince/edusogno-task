<?php
/**
 * Project: edusogno-task
 * File: login.php
 * Author: wanpeninsula
 * Organization: Edusogno
 * Author URI: https://www.pennycodes.dev
 * Created: 09/10/2023 at 10:04 pm
 *
 * Copyright (c) 2023 Edusogno. All rights reserved.
 */
Routes::$pageData['title'] = "Login to Edusogno";
Routes::$pageData['description'] = "This is the login page";
Routes::$pageData['keywords'] = EdusognoApp::config('site_keywords');
Routes::$pageData['page'] = "Login";
Routes::$pageData['content'] = Routes::loadSitePage('login/content');
