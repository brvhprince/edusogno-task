<?php
/**
 * Project: edusogno-task
 * File: register.php
 * Author: wanpeninsula
 * Organization: Edusogno
 * Author URI: https://www.pennycodes.dev
 * Created: 10/10/2023 at 12:13 pm
 *
 * Copyright (c) 2023 Edusogno. All rights reserved.
 */
Routes::$pageData['title'] = "Create an account on Edusogno";
Routes::$pageData['description'] = "This is the sign up page";
Routes::$pageData['keywords'] = EdusognoApp::config('site_keywords');
Routes::$pageData['page'] = "Register";
Routes::$pageData['content'] = Routes::loadSitePage('register/content');
