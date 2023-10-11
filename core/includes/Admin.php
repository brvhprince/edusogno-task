<?php

/**
 * Project: edusogno-task
 * File: Admin.php
 * Author: wanpeninsula
 * Organization: Edusogno
 * Author URI: https://www.pennycodes.dev
 * Created: 10/10/2023 at 10:16 pm
 *
 * Copyright (c) 2023 Edusogno. All rights reserved.
 */
class Admin
{
    public function login (string $email, string $password, bool $rememberMe = false): bool {

        $sql = "SELECT * FROM ".T_ADMIN." WHERE email = ?";
        $stmt = Database::getConnection()->prepare($sql);
        $email = Utils::test_input($email);
        $stmt->bind_param('s', $email);
        $stmt->execute();

        $result = $stmt->get_result();
        $admin = $result->fetch_assoc();

        if (!empty($admin)) {
            if (Utils::passwordCheck($password, $admin['password'])) {
                $this->setAdminSession($admin['admin_id'], $rememberMe);
                return true;
            }
            return false;
        }
        return false;
    }


    private function setAdminSession(string $admin_id, bool $rememberMe): void
    {
        $adminToken = bin2hex(openssl_random_pseudo_bytes(24));
        $uid =  Utils::encode($admin_id);
        Session::set('admin_id', $uid);
        Session::set('admin_token', $adminToken);
        if ($rememberMe) {
            $expireTime = time() + 86400;
            $token_encryption = Utils::encode($adminToken);
            $id_encryption = Utils::encode($uid);

            $tokenCookieName = Utils::encode("adminToken");
            $IdCookieName = Utils::encode("adminID");

            Cookie::set($tokenCookieName, $token_encryption, $expireTime);
            Cookie::set($IdCookieName, $id_encryption, $expireTime);
        }

    }

    /**
     * This method fetches an admin by admin_id
     * @param string $admin_id
     * @return array
     */
    public function fetchAdmin (string $admin_id): array
    {
        $sql = "SELECT * FROM " . T_ADMIN . " WHERE admin_id = ? OR email = ?";
        $stmt = Database::getConnection()->prepare($sql);
        $admin_id = Utils::test_input($admin_id);
        $stmt->bind_param('ss', $admin_id, $admin_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();

        $data = [];
        if (!empty($row)) {
            $data['id'] = $row['admin_id'];
            $data['first_name'] = Utils::test_output($row['first_name']);
            $data['last_name'] = Utils::test_output($row['last_name']);
            $data['email'] = Utils::test_output($row['email']);
            $data['name'] = $data['first_name'] . ' ' . $data['last_name'];
            $data['initials'] = Utils::generateInitials($data['name']);

            Utils::getTimeStamps(EdusognoApp::config('date_style'), $row, $data);
        }
        return $data;
    }
}

