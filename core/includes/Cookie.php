<?php

/**
 * Project: edusogno-task
 * File: Cookie.php
 * Author: wanpeninsula
 * Organization: Edusogno
 * Author URI: https://www.pennycodes.dev
 * Created: 09/10/2023 at 9:58 pm
 *
 * Copyright (c) 2023 Edusogno. All rights reserved.
 */
class Cookie
{
    public static function set(string $name, string $value, int $expiry): bool
    {
        if (setcookie($name, $value, time() + $expiry, '/')) {
            return true;
        }
        return false;
    }

    public static function delete(string $name): void
    {
        unset($_COOKIE[$name]);
        self::set($name, '', time() - 1);
    }

    public static function get(string $name): string
    {
        return $_COOKIE[$name] ?? '';
    }

    public static function exists(string $name): bool
    {
        return isset($_COOKIE[$name]);
    }

}
