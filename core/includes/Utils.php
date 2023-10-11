<?php

use JetBrains\PhpStorm\NoReturn;

/**
 * Project: edusogno-task
 * File: Utils.php
 * Author: wanpeninsula
 * Organization: Edusogno
 * Author URI: https://www.pennycodes.dev
 * Created: 09/10/2023 at 6:40 pm
 *
 * Copyright (c) 2023 Edusogno. All rights reserved.
 */
class Utils
{

    private static string $template_dir = './templates/';
    /**
     * This method cleans an input string.
     * @param string $string
     * @return array|string|string[]|null
     * @uses preg_replace()
     */
    public static function cleanString(string $string): array|string|null
    {
        return  preg_replace("/&#?[a-z\d]+;/i","", $string);
    }

    /**
     * This method sanitizes and secures an input string.
     * @param $string
     * @param bool $br
     * @param bool $strip
     * @return string
     * @uses cleanString()
     */
    public static function secureInput($string, bool $br = false, bool $strip = false): string
    {
       $db = Database::getConnection();
        if (empty($string)) return '';
        $string = trim($string);
        $string = self::cleanString($string);
        $string = mysqli_real_escape_string($db, $string);
        $string = htmlspecialchars($string, ENT_QUOTES);
        if ($br) {
            $string = str_replace('\r\n', " <br>", $string);
            $string = str_replace('\n\r', " <br>", $string);
            $string = str_replace('\r', " <br>", $string);
            $string = str_replace('\n', " <br>", $string);
        } else {
            $string = str_replace('\r\n', "", $string);
            $string = str_replace('\n\r', "", $string);
            $string = str_replace('\r', "", $string);
            $string = str_replace('\n', "", $string);
        }
        if ($strip == 1) {
            $string = stripslashes($string);
        }
        return str_replace('&amp;#', '&#', $string);
    }

    /**
     * This method handles catchable errors
     * @param string $title
     * @param \Exception $e
     * @param string $meta
     * @return void
     */
    public static function logError(string $title, \Exception $e, string $meta = ''): void
    {
        error_log("\n\n");
        error_log('------------LOG BEGIN---------------');
        error_log("Title   :   $title");
        error_log("Message   :   ".$e->getMessage());
        error_log("Code   :   ".$e->getCode());
        error_log("Line   :   ".$e->getLine());
        error_log("File   :   ".$e->getFile());
        error_log("Trace   :   ".$e->getTraceAsString());
        error_log("Meta   :   ".$meta);
        error_log('------------LOG END---------------');
        error_log("\n\n");
    }


    /**
     * This method redirects to a page.
     * @param string $location
     * @return void
     */
    #[NoReturn] public static function redirect(string $location): void
    {
        header("Location: $location");
        exit();
    }

    /**
     * This method calculates time ago between two dates.
     * @param $date
     * @param null $now
     * @return string
     */
    public static function timeAgo($date, $now = null): string
    {
        if (empty($date)) {
            return "No date provided";
        }
        if (empty($now)) {
            $now = time();
        }
        else {
            $now = strtotime($now);
        }

        $periods = array("second", "minute", "hour", "day", "week", "month", "year", "decade");
        $lengths = array("60", "60", "24", "7", "4.35", "12", "10");

        $unix_date = strtotime($date);
        // check validity of date
        if (empty($unix_date)) {
            return "Bad date";
        }
        // is it future date or past date
        if ($now > $unix_date) {
            $difference = $now - $unix_date;
            $tense = "ago";
        } else {
            $difference = $unix_date - $now;
            $tense = "from now";
        }
        for ($j = 0; $difference >= $lengths[$j] && $j < count($lengths) - 1; $j++) {
            $difference /= $lengths[$j];
        }
        $difference = round($difference);
        if ($difference != 1) {
            $periods[$j] .= "s";
        }
        return "$difference $periods[$j] {$tense}";
    }

    /**
     * This method encodes and decodes a string.
     * @param $string
     * @param string $action
     * @return false|string
     */
    public static function encode($string, string $action = 'e'): bool|string
    {
        $secret_key = 'developer@pennycodes@key@edusogna';
        $secret_iv = 'developer@pennycodes@iv@edusogna';

        $output = false;
        $encrypt_method = "AES-256-CBC";
        $key = hash( 'sha256', $secret_key );
        $iv = substr( hash( 'sha256', $secret_iv ), 0, 16 );

        if( $action == 'e' ) {
            $output = base64_encode( openssl_encrypt( $string, $encrypt_method, $key, 0, $iv ) );
        }
        else if( $action == 'd' ){
            $output = openssl_decrypt( base64_decode( $string ), $encrypt_method, $key, 0, $iv );
        }

        return $output;
    }

    /**
     * This method sanitizes db input data.
     * @param $data
     * @return string
     * @uses htmlspecialchars()
     */
    public static function test_input($data): string
    {
        if ($data != '0' && empty($data)) return '';
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlentities($data, ENT_QUOTES);
        return htmlspecialchars($data);
    }

    /**
     * This method sanitizes db output data.
     * @param $data
     * @param bool $tags
     * @return string
     * @uses htmlspecialchars_decode()
     */
    public static function test_output($data, bool $tags = false): string
    {
        if (empty($data)) return '';
        $data = htmlspecialchars_decode($data);
        $data = html_entity_decode($data, ENT_QUOTES);
        $data =  htmlspecialchars_decode($data);
        if ($tags) $data = strip_tags($data);
        return $data;
    }

    /**
     * This method validates an email address.
     * @param string $email
     * @return bool
     */
    public static function validateEmail(string $email): bool
    {
        $email = trim($email);
        return filter_var($email, FILTER_VALIDATE_EMAIL);
    }

    /**
     * This method encrypts a password.
     * @param string $password
     * @return string|null
     */
    public static function passwordEncryption(string $password): ?string
    {
        $BlowFish_Hash_Format = "$2y$10$";
        $Salt_Length = 22;
        $Salt = self::generateSalt($Salt_Length);
        $Formatting_BlowFish_With_Salt = $BlowFish_Hash_Format . $Salt;
        return crypt($password, $Formatting_BlowFish_With_Salt);
    }

    /**
     * This method generates a random salt.
     * @param int $length
     * @return string
     */
    public static function generateSalt(int $length): string
    {
        $Unique_Random_String = md5(uniqid(mt_rand(), true));

        $Base64_String = base64_encode($Unique_Random_String);

        $Modified_Base64_String = str_replace('+', '.', $Base64_String);

        return substr($Modified_Base64_String, 0, $length);
    }

    /**
     * This method checks if a password is correct.
     * @param string $password
     * @param string $existing_hash
     * @return bool
     */
    public static function passwordCheck(string $password, string $existing_hash): bool
    {
        $hash = crypt($password, $existing_hash);
        return $hash === $existing_hash;
    }

    /**
     * This method checks the strength of a password.
     * @param string $password
     * @return bool
     */
    public static function passwordStrength(string $password): bool
    {
        $letter = preg_match('@[a-zA-Z]@', $password);
        $number = preg_match('@\d@', $password);
        $specialChars = preg_match('@\W@', $password);

        if (!$letter || !$number || !$specialChars || strlen($password) < 6) {
            return true;
        }else{
            return false;
        }
    }

    /**
     * This method generates a random string with a given length
     * @param int $len
     * @return string
     */
    public static function generateRandomString(int $len = 10): string
    {
        return bin2hex(openssl_random_pseudo_bytes($len));
    }

    /**
     * This generates initials from a string
     * @param $string
     * @param bool $onlyCapitals
     * @return string|null
     */
    public static function generateInitials($string, bool $onlyCapitals = true): ?string
    {
        $output = null;
        $token  = strtok($string, ' ');
        while ($token !== false) {
            $character = mb_substr($token, 0, 1);
            if ($onlyCapitals and mb_strtoupper($character) !== $character) {
                $token = strtok(' ');
                continue;
            }
            $output .= $character;
            $token = strtok(' ');
        }
        return strlen($output) > 2 ? substr($output, 0, 2) : $output;
    }

    /**
     * This method parses variables to a string template
     * @param string $template
     * @param array $variables
     * @return string
     */
    public static function parseTemplate(string $template, array $variables): string
    {
        return preg_replace_callback('/\{\s*([a-zA-Z0-9_]+)\s*}/', function ($matches) use ($variables) {
            return $variables[$matches[1]] ?? '';
        }, $template);
    }

    /**
     * This method fetches a template from the template dir
     * @param string $template_name
     * @return string
     */
    public static function fetchTemplate(string $template_name): string
    {
        $template = self::$template_dir . $template_name . '.html';
        if (file_exists($template)) {
            return file_get_contents($template);
        }
        return '';
    }

    /**
     * This method creates a new unique ID
     * @param string $prefix
     * @param string $type
     * @return string
     */
    public static function createID(string $prefix = '', string $type = 'user'): string
    {
        try {
            $uid = $prefix . random_int(1111111, 9999999);

        } catch (Exception $e) {
            $uid = $prefix . rand(1111111, 9999999);
        }
        if (self::validateUID($uid, $type)) return self::createID($prefix, $type);

        return $uid;
    }

    /**
     * This method validates a UID
     * @param $uid
     * @param string $type
     * @return bool
     */
    public static function validateUID($uid, string $type = 'user'): bool
    {
        if (empty($uid)) return false;

        if ($type ==='user'){
            $sql = "SELECT * FROM ".T_USERS." WHERE user_id = ? ";
        }
        elseif ($type === 'event') {
            $sql = "SELECT * FROM ".T_EVENTS." WHERE event_id = ? ";
        }
        else {

            $sql = "SELECT * FROM ".T_ADMIN." WHERE admin_id = ? ";
        }
        $stmt = Database::getConnection()->prepare($sql);
        $stmt->bind_param('s', $uid);
        $stmt->execute();
        $stmt -> store_result();
        if ($stmt->num_rows > 0) {
            return true;
        }
        else {
            return false;
        }
    }

    /**
     * This method converts datetime to readable format
     * @param $date_style
     * @param array $fetched_data
     * @param array $data
     * @return void
     */
    public static function getTimeStamps($date_style, array $fetched_data, array &$data): void
    {
        $data['created'] = $date_style === 'time_ago' ? self::timeAgo($fetched_data['createdAt']) : date_format(date_create($fetched_data['createdAt']), $date_style);
        $data['updated'] = $date_style === 'time_ago' ? self::timeAgo($fetched_data['updatedAt']) : date_format(date_create($fetched_data['updatedAt']), $date_style);
    }

}
