<?php

/**
 * Project: edusogno-task
 * File: Database.php
 * Author: wanpeninsula
 * Organization: Edusogno
 * Author URI: https://www.pennycodes.dev
 * Created: 09/10/2023 at 6:44 pm
 *
 * Copyright (c) 2023 Edusogno. All rights reserved.
 */

use JetBrains\PhpStorm\ArrayShape;

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
class Database
{
    static MySQLi $db;

    /**
     * This method connects to the database.
     * @return bool
     * @uses mysqli_connect()
     */
    public static function connect(): bool
    {
        if (!isset(self::$db)) {
            try {
                self::$db =  mysqli_connect($_SERVER['DB_HOST'],$_SERVER['DB_USER'],$_SERVER['DB_PASS'],$_SERVER['DB_NAME'],$_SERVER['DB_PORT']);
                if (self::$db->connect_errno) {
                        Utils::logError('Database Connection Error', new Exception(self::$db->connect_error));
                   include 'error.php';
                     exit();
                }
                return true;
            } catch (Exception $e) {
                Utils::logError('Database Connection Error', $e);
                include 'error.php';
                exit();
            }

        }
        return true;
    }

    /**
     * This method returns database connection details
     * @return array
     */
#[ArrayShape(['host' => "mixed", 'user' => "mixed", 'pass' => "mixed", 'db' => "mixed", 'port' => "mixed"])] public static function getDetails(): array
    {
        return [
            'host' => $_SERVER['DB_HOST'],
            'user' => $_SERVER['DB_USER'],
            'pass' => $_SERVER['DB_PASS'],
            'db' => $_SERVER['DB_NAME'],
            'port' => $_SERVER['DB_PORT']
        ];
    }

    /**
     * This method returns the database connection
     * @return MySQLi
     */
    public static function getConnection(): MySQLi
    {
        return self::$db;
    }

    /**
     * This method closes the database connection
     * @return bool
     */
    public static function close(): bool
    {
        if (isset(self::$db)) {
            self::$db->close();
            return true;
        }
        return false;
    }

    /**
     * This method executes a query
     * @param string $query
     * @return bool|mysqli_result
     * @uses mysqli_query()
     */
    public static function query(string $query): bool|mysqli_result
    {
        return self::$db->query($query);
    }

    /**
     * This method returns the last inserted id
     * @return int|string
     * @uses mysqli_insert_id()
     */
    public static function lastId(): int|string
    {
        return self::$db->insert_id;
    }

    /**
     * This method returns the number of rows affected by the last query
     * @return int
     * @uses mysqli_affected_rows()
     */
    public static function affectedRows(): int
    {
        return self::$db->affected_rows;
    }

    /**
     * This method returns the number of rows returned by the last query
     * @param mysqli_result $result
     * @return int
     * @uses mysqli_num_rows()
     */
    public static function numRows(mysqli_result $result): int
    {
        return $result->num_rows;
    }

    /**
     * This method returns the last error
     * @return string
     * @uses mysqli_error()
     */
    public static function error(): string
    {
        return self::$db->error;
    }

    /**
     * This method returns the last error number
     * @return int
     * @uses mysqli_errno()
     */
    public static function errorNumber(): int
    {
        return self::$db->errno;
    }

}
