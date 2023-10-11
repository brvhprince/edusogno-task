<?php

use JetBrains\PhpStorm\ArrayShape;

/**
 * Project: edusogno-task
 * File: Session.php
 * Author: wanpeninsula
 * Organization: Edusogno
 * Author URI: https://www.pennycodes.dev
 * Created: 09/10/2023 at 7:10 pm
 *
 * Copyright (c) 2023 Edusogno. All rights reserved.
 */
class Session
{

    public static function start(): void
    {
        session_name('edusogno');
        session_start();
    }

    public static function set(string $key, $value): void
    {
        $_SESSION[$key] = $value;
    }

    public static function get(string $key)
    {
        return $_SESSION[$key];
    }

    public static function delete(string $key): void
    {
        unset($_SESSION[$key]);
    }

    public static function destroy(): void
    {
        session_destroy();
    }

    public static function exists(string $key): bool
    {
        return isset($_SESSION[$key]);
    }

    /**
     * This method checks session expiration.
     * @return bool
     */
    public static function checkExpiration(): bool
    {
      if (!self::exists('time_to_live')) {
        self::set('time_to_live', time());
        return true;
      }
      else if (time() - self::get('time_to_live') > 1800) {
        // session expired after 30 minutes of inactivity
          self::set('time_to_live', time());

          self::createFormToken(false);
          self::createMainSession(false);
        return false;
      }
     return true;
    }


    /**
     * This method creates a hash session for authorized form submissions.
     * @param bool $state
     * @return string
     */
    public static function createFormToken(bool $state = true): string
    {
       if (self::checkExpiration() === $state && self::exists('hash_id')) {
        return self::get('hash_id');
       }

        try {
            $hash = bin2hex(random_bytes(32));
        } catch (\Exception $e) {
            $hash = base64_encode(mt_rand()+mt_rand());
        }

        self::set('hash_id', $hash);
        return $hash;
    }

    /**
     * This method creates application main session.
     * @param bool $state
     * @return string
     */
    public static function createMainSession(bool $state = true): string
    {
        if (self::checkExpiration() === $state && self::exists('main_session')) {
            return self::get('main_session');
        }

        try {
            $hash = bin2hex(random_bytes(32));
        } catch (\Exception $e) {
            $hash = base64_encode(mt_rand()+mt_rand());
        }

        self::set('main_session', $hash);
        return $hash;
    }


    /**
     * This method checks form token session.
     * @param string $hash
     * @return bool
     */
    public static function checkFormToken(string $hash): bool
    {
        if (!self::exists('hash_id') || empty($hash)) {
            return false;
        }

        if (hash_equals(self::get('hash_id'), $hash) && self::checkExpiration()) {
            return true;
        }

        return false;
    }
    /**
     * This method checks main application session status.
     * @param string $hash
     * @return bool
     */
    public static function checkMainSession(string $hash): bool
    {
        if (!self::exists('main_session') || empty($hash)) {
            return false;
        }

        if (hash_equals(self::get('main_session'), $hash) && self::checkExpiration()) {
            return true;
        }

        return false;
    }

    /**
     * This method checks if a user is logged in.
     * @return bool
     */
    public static function isUser(): bool
    {
        $cookieName = Utils::encode('userID');
        if (self::exists('user_id') || Cookie::exists($cookieName)) {
            return true;
        }
        return false;
    }

    /**
     * This method checks if an admin is logged in.
     * @return bool
     */
    public static function isAdmin(): bool
    {
        $cookieName = Utils::encode('adminID');
        if (self::exists('admin_id') || Cookie::exists($cookieName)) {
            return true;
        }
        return false;
    }

    /**
     * This method destroys user session.
     */
    public static function destroyUserSession(): void
    {
        self::delete('user_id');
        self::delete('user_token');

        $IDCookieName = Utils::encode('userID');
        $tokenCookieName = Utils::encode('userToken');

        if (Cookie::exists($IDCookieName)) {
            Cookie::delete($IDCookieName);
        }
        if (Cookie::exists($tokenCookieName)) {
            Cookie::delete($tokenCookieName);
        }

        self::destroy();

    }

    /**
     * This method destroys admin session.
     */
    public static function destroyAdminSession(): void
    {
        self::delete('admin_id');
        self::delete('admin_token');

        $IDCookieName = Utils::encode('adminID');
        $tokenCookieName = Utils::encode('adminToken');

        if (Cookie::exists($IDCookieName)) {
            Cookie::delete($IDCookieName);
        }
        if (Cookie::exists($tokenCookieName)) {
            Cookie::delete($tokenCookieName);
        }

        self::destroy();

    }


    /**
     * This method gets details of an authenticated user
     * @return array
     */
    #[ArrayShape(['user' => "mixed", 'token' => "bool|mixed|string"])] public static function getUser(): array
    {

        $cookie_name = Utils::encode('userID');
        $cookie_token = Utils::encode('userToken');
        if (self::exists('user_id')) {
            $id = Utils::encode(self::get('user_id'), 'd');
            $user = (new User)->fetchUser($id);
            return ['user' => $user, 'token' => self::get('user_token')];
        }
        else if (Cookie::exists($cookie_name) && Cookie::exists($cookie_token)) {
            $id = (Utils::encode(Cookie::get($cookie_name), 'd'));
            $id = (Utils::encode($id, 'd'));
            $user = (new User)->fetchUser($id);
            if (empty($user)) {
                Session::destroyUserSession();
            }
            return ['user' => $user, 'token' => Utils::encode(Cookie::get($cookie_token), 'd')];
        }

        return ['user' => null, 'token' => false];
    }

    /**
     * This method gets details of an authenticated admin
     * @return array
     */
    #[ArrayShape(['admin' => "mixed", 'token' => "bool|mixed|string"])] public static function getAdmin(): array
    {

        $cookie_name = Utils::encode('adminID');
        $cookie_token = Utils::encode('adminToken');
        if (self::exists('admin_id')) {
            $id = Utils::encode(self::get('admin_id'), 'd');
            $admin = (new Admin)->fetchAdmin($id);
            return ['admin' => $admin, 'token' => self::get('admin_token')];
        }
        else if (Cookie::exists($cookie_name) && Cookie::exists($cookie_token)) {
            $id = (Utils::encode(Cookie::get($cookie_name), 'd'));
            $id = (Utils::encode($id, 'd'));
            $admin = (new Admin)->fetchAdmin($id);
            if (empty($admin)) {
                Session::destroyAdminSession();
            }
            return ['admin' => $admin, 'token' => Utils::encode(Cookie::get($cookie_token), 'd')];
        }

        return ['admin' => null, 'token' => false];
    }
}


