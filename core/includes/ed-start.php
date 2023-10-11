<?php
/**
 * Project: edusogno-task
 * File: ed-start.php
 * Author: wanpeninsula
 * Organization: Edusogno
 * Author URI: https://www.pennycodes.dev
 * Created: 09/10/2023 at 6:51 pm
 *
 * Copyright (c) 2023 Edusogno. All rights reserved.
 */

/**********************************************************************************************************************/
// This file is the main configuration entry of the application.
/**********************************************************************************************************************/

EdusognoApp::start();

/**This function creates a global exception error handler.
 * @param int $severity
 * @param string $message
 * @param string $file
 * @param int $line
 * @return bool
 * @throws ErrorException
 */
function exception_error_handler(int $severity,string $message,string $file, int $line): bool

{
    throw new ErrorException($message, 0, $severity, $file, $line);

}

set_error_handler("exception_error_handler");


$app = new EdusognoApp();

$app->registerApps([
    'db' => Database::class,
    'mail' => new Mailing(),
    'utils' => Utils::class,
    'event' => new Event(),
    'user' => new User()
]);

$app->fetchConfigs();

