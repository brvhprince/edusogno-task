<?php

use JetBrains\PhpStorm\NoReturn;

/**
 * Project: edusogno-task
 * File: Routes.php
 * Author: wanpeninsula
 * Organization: Edusogno
 * Author URI: https://www.pennycodes.dev
 * Created: 09/10/2023 at 7:28 pm
 *
 * Copyright (c) 2023 Edusogno. All rights reserved.
 */
class Routes
{
    private static string $admin_path = "./admin-panel/pages/";
    private static string $user_path = "./layouts/";
    private static string $admin_route = "/admin-cp/";
    private static string $public_route = "/public/";
    private static string $admin_assets_route = "/admin-cp/assets/";

    public static array $pageData = [];

    /**
     * This method loads admin page content
     * @param string $page_url
     * @return string
     */
    public static function loadAdminPage(string $page_url = ''): string
    {
        $page        = self::$admin_path . $page_url . '.phtml';
        $page_content = '';

        ob_start();
        require $page;
        $page_content = ob_get_contents();
        ob_end_clean();

        return $page_content;
    }

    /**
     * This method loads admin page link
     * @param string $link
     * @return string
     */
    public static function loadAdminLink(string $link = ''): string
    {
        $site_url = EdusognoApp::config('site_url');
        return $site_url . self::$admin_route . $link;
    }

    /**
     * This method loads site link
     * @param string $link
     * @return string
     */
    public static function loadSiteLink(string $link = ''): string
    {
        $site_url = EdusognoApp::config('site_url');
        return $site_url ."/". $link;
    }

    /**
     * This method loads public assets
     * @param string $path
     * @return string
     */
    public static function loadPublicAssets(string $path = ''): string
    {
        $site_url = EdusognoApp::config('site_url');

        return $site_url . self::$public_route . $path;
    }

    /**
     * This method loads admin asset link
     * @param string $file
     * @return string
     */
    public static function loadAdminAssets(string $file = ''): string
    {
        $site_url = EdusognoApp::config('site_url');
        return $site_url . self::$admin_assets_route . $file;
    }

    /**
     * This method loads site page link
     * @param string $page_url
     * @return string|false
     */
    public static function loadSitePage(string $page_url = ''): string|false
    {
        $page        = self::$user_path . $page_url . '.phtml';
        $page_content = '';

        ob_start();
        require $page;
        $page_content = ob_get_contents();
        ob_end_clean();

        return $page_content;
    }

    /**
     * This method redirects to a page.
     * @param string $link
     * @param string $route admin or user
     * @return void
     */
    #[NoReturn] public static function redirect(string $link = '', string $route = 'user'): void
    {
        if ($route == 'user') {
            header("Location: " . self::loadSiteLink($link));
        } else {
            header("Location: " . self::loadAdminLink($link));
        }
        exit();
    }

    public static function siteUrl(): string
    {
        return EdusognoApp::config('site_url');
    }
}
