<?php

/**
 * Project: edusogno-task
 * File: EdusognoApp.php
 * Author: wanpeninsula
 * Organization: Edusogno
 * Author URI: https://www.pennycodes.dev
 * Created: 09/10/2023 at 6:53 pm
 *
 * Copyright (c) 2023 Edusogno. All rights reserved.
 */
class EdusognoApp
{
    private stdClass $apps;

    public  array $config;
    public static array $configs;

    public static array $user;
    public static array $admin;

    public function __construct()
    {
        $this->apps = new stdClass();
    }

    public static function start(): void
    {
        Session::start();
        Database::connect();
    }

    public function registerApps(array $apps): void
    {
        foreach ($apps as $name => $class) {
          $this->apps->$name = $class;
        }
    }

    public static  function config(string $name): string
    {
        return self::$configs[$name];
    }

    public static function configs(): array
    {
        return self::$configs;
    }

    public function app(string $name): mixed
    {
        return $this->apps->$name;
    }

    public function apps(): stdClass
    {
        return $this->apps;
    }

    public function fetchConfigs(): void
    {
       $sql = "SELECT * FROM ".T_CONFIG;
       $results = Database::query($sql);

       $data = [];
       if (!empty($results)) {
           foreach ($results as $fetched_data) {
               $data[$fetched_data['name']] = $fetched_data['value'];
           }
       }
       else {
           die("SQL file not imported.");
       }

         self::$configs = $data;
    }

    /**
     * This method updates system configuration.
     * @param string $update_name
     * @param string $value
     * @return bool
     */
    public function saveConfig(string $update_name, string $value): bool {
        if (!array_key_exists($update_name, self::configs())) {
            return false;
        }

        $db = Database::getConnection();

        $update_name = Utils::test_input($update_name);
        $value       = mysqli_real_escape_string($db, $value);
        $value = Utils::test_input($value);
        $sql = "UPDATE ". T_CONFIG. " SET value = ? WHERE name = ?";
        $stmt = $db->prepare($sql);
        $stmt->bind_param('ss',$value,$update_name);
        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }

    public static function user(): array
    {
        return self::$user;
    }

    public static function setUser(array $user): void
    {
        self::$user = $user;
    }

    public static function admin(): array
    {
        return self::$admin;
    }

    public static function setAdmin(array $admin): void
    {
        self::$admin = $admin;
    }


}
