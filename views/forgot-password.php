<?php
/**
 * Project: edusogno-task
 * File: forgot-password.php
 * Author: wanpeninsula
 * Organization: Edusogno
 * Author URI: https://www.pennycodes.dev
 * Created: 10/10/2023 at 6:53 pm
 *
 * Copyright (c) 2023 Edusogno. All rights reserved.
 */
Routes::$pageData['title'] = "Forgot Password to Edusogno";
Routes::$pageData['description'] = "This is the forgot password page";
Routes::$pageData['keywords'] = EdusognoApp::config('site_keywords');
Routes::$pageData['page'] = "Forgot Password";
Routes::$pageData['content'] = Routes::loadSitePage('forgot-password/content');
