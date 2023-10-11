<?php

/**
 * Project: edusogno-task
 * File: Helpers.php
 * Author: wanpeninsula
 * Organization: Edusogno
 * Author URI: https://www.pennycodes.dev
 * Created: 10/10/2023 at 7:11 pm
 *
 * Copyright (c) 2023 Edusogno. All rights reserved.
 */
class Helpers
{
    /**
     * This method sends password reset instructions to a user
     * @param string $email
     * @return bool|string
     */
    public static function sendPasswordResetInstructions(string $email): bool|string
    {
        $userController = new User();
        $user = $userController->fetchUser($email);
        if (empty($user)) {
            return false;
        }

        $code = self::createVerificationCode($email);

        if (empty($code)) {
            return false;
        }

        $template = Utils::fetchTemplate('mail/password-reset');


        $name = $user['first_name'];
        $vars = [
            'name' => $name,
            'site_name' => EdusognoApp::config('site_name'),
            'reset_link' => Routes::loadSiteLink("reset-password/$code"),
            'date' => date('Y'),
            'date_requested' => date('d M Y \a\t h:i A')
        ];

        $message = Utils::parseTemplate($template, $vars);

        // fallback in case template couldn't be loaded and parsed
        if (empty($message)) {
            $message ="Hello $name, your password reset link is: ".Routes::loadSiteLink("reset-password/$code");
        }

        $mailer = new Mailing();

        $subject =  EdusognoApp::config('site_name') . " - Account Password Reset";
        $mailer->setPayload($name, $email, $subject, $message);
        return $mailer->send();
    }

    /**
     * This method creates, saves and returns a new verification code for a user
     * @param string $email
     * @return false|string
     */
    public static function createVerificationCode(string $email): false|string
    {
        $expires = strtotime('+30 minutes');
        $code = Utils::generateRandomString(3);
        $email = Utils::test_input($email);

        $sql = "INSERT INTO ".T_VERIFICATION." (email, code, expires) VALUES (?, ?, ?)";
        $stmt = Database::getConnection()->prepare($sql);
        $stmt->bind_param('ssi', $email, $code, $expires);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->affected_rows > 0) {
            return $code;
        }
        return false;
    }

    /**
     * This method checks if a user has a pending verification code.
     * This method checks if the current code is less than 30 minutes old
     * @param string $email
     * @return bool
     */
    public static function hasPendingVerificationCode(string $email): bool
    {
        $email = Utils::test_input($email);
        $sql = "SELECT expires, code FROM ".T_VERIFICATION." WHERE email = ?";
        $stmt = Database::getConnection()->prepare($sql);
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $stmt->bind_result( $expires, $code);
        $stmt->store_result();
        $stmt->fetch();

        if ($stmt->num_rows > 0) {
            $current = time();
            if ($current < $expires) {
                return true;
            }
            else {
                self::removeVerificationCode($code);
                return false;
            }
        }
        return false;
    }

    /**
     * This method checks if a verification code is valid
     * @param string $code
     * @return bool|string
     */
    public static function isValidVerificationCode(string $code): bool|string
    {
        $code = Utils::test_input($code);
        $sql = "SELECT expires, email FROM ".T_VERIFICATION." WHERE code = ?";
        $stmt = Database::getConnection()->prepare($sql);
        $stmt->bind_param('s', $code);
        $stmt->execute();
        $stmt->bind_result($expires, $email);
        $stmt->store_result();
        $stmt->fetch();

        if ($stmt->num_rows > 0) {
            $current = time();
            if ($current < $expires) {
                self::removeVerificationCode($code);
                // return the email address
                return $email;
            }
            else {
                // Delete the expired code
              return self::removeVerificationCode($code);
            }
        }
        return false;
    }

    /**
     * This method removes a verification code from the database
     * @param string $code
     * @return bool
     */
    public static function removeVerificationCode(string $code): bool
    {
        $sql = "DELETE FROM ".T_VERIFICATION." WHERE code = ?";
        $stmt = Database::getConnection()->prepare($sql);
        $stmt->bind_param('s', $code);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            return true;
        }
        return false;
    }
}
