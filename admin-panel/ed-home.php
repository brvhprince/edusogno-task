<?php
/**
 * Project: edusogno-task
 * File: ed-home.php
 * Author: wanpeninsula
 * Organization: Edusogno
 * Author URI: https://www.pennycodes.dev
 * Created: 10/10/2023 at 10:12 pm
 *
 * Copyright (c) 2023 Edusogno. All rights reserved.
 */

$http_header           = 'http://';
if (!empty($_SERVER['HTTPS'])) {
    $http_header = 'https://';
}
$this_url   = $http_header . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

$this_url = str_replace('admin-panel', 'admin-cp', $this_url);
header("Location: $this_url");
exit();
?>
You can access the admin panel, from <a href="<?=$this_url ?>"><?=$this_url ?></a>
