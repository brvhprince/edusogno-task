<?php
/**
 * Project: edusogno-task
 * File: init.php
 * Author: wanpeninsula
 * Organization: Edusogno
 * Author URI: https://www.pennycodes.dev
 * Created: 09/10/2023 at 4:50 pm
 *
 * Copyright (c) 2023 Edusogno. All rights reserved.
 */

@ini_set('session.cookie_httponly',1);
@ini_set('session.use_only_cookies',1);
error_reporting(E_ALL);
ini_set('ignore_repeated_errors', TRUE);
ini_set('display_errors',false);
ini_set('log_errors',TRUE);
ini_set('error_log', 'errors.log');
@header('Access-Control-Allow-Origin: *');
@header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
@header("Access-Control-Allow-Headers: X-Requested-With, Content-Type, Accept, Origin, Authorization, Cache-Control, Pragma, Accept-Encoding, DM-Agent");
@header('X-Powered-By: Edusogno');
@header('X-Frame-Options: SAMEORIGIN');
@header('X-XSS-Protection: 1; mode=block');
@header('X-Content-Type-Options: nosniff');
@header('X-Permitted-Cross-Domain-Policies: none');
@header('X-Download-Options: noopen');

if (!version_compare(PHP_VERSION, '8.0', '>=')) {
    exit("Required PHP_VERSION >= 8.0 , Your PHP_VERSION is : " . PHP_VERSION . "\n");
}

date_default_timezone_set('Africa/Accra');
require_once __DIR__ . "/../vendor/autoload.php";

$dotenv = Dotenv\Dotenv::createUnsafeImmutable(__DIR__.'/../');
$dotenv->safeLoad();

$dotenv->required(['DB_HOST', 'DB_NAME', 'DB_USER', 'DB_PASS', 'DB_PORT']);
$dotenv->required('DB_HOST')->notEmpty();
$dotenv->required('DB_NAME')->notEmpty();
$dotenv->required('DB_USER')->notEmpty();
$dotenv->required('DB_PORT')->notEmpty();
$dotenv->required('DB_PORT')->isInteger();

require_once "includes/tables.php";
require_once "includes/Database.php";
require_once "includes/Session.php";
require_once "includes/Cookie.php";
require_once "includes/Routes.php";
require_once "includes/Mailing.php";
require_once "includes/Helpers.php";
require_once "includes/Event.php";
require_once "includes/User.php";
require_once "includes/Admin.php";
require_once "includes/Utils.php";
require_once "includes/EdusognoApp.php";
require_once "includes/ed-start.php";
