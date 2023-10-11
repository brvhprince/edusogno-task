<?php

/**
 * Project: edusogno-task
 * File: User.php
 * Author: wanpeninsula
 * Organization: Edusogno
 * Author URI: https://www.pennycodes.dev
 * Created: 09/10/2023 at 10:49 pm
 *
 * Copyright (c) 2023 Edusogno. All rights reserved.
 */
class User
{

    public function login (string $email, string $password, bool $rememberMe = false): bool {

        $sql = "SELECT * FROM ".T_USERS." WHERE email = ?";
        $stmt = Database::getConnection()->prepare($sql);
        $email = Utils::test_input($email);
        $stmt->bind_param('s', $email);
        $stmt->execute();

        $result = $stmt->get_result();
        $user = $result->fetch_assoc();

        if (!empty($user)) {
            if (Utils::passwordCheck($password, $user['password'])) {
               $this->setUserSession($user['user_id'], $rememberMe);
                return true;
            }
            return false;
        }
        return false;
    }

    private function setUserSession(string $user_id, bool $rememberMe): void
    {
        $userToken = bin2hex(openssl_random_pseudo_bytes(24));
        $uid =  Utils::encode($user_id);
        Session::set('user_id', $uid);
        Session::set('user_token', $userToken);
        if ($rememberMe) {
            $expireTime = time() + 86400;
            $token_encryption = Utils::encode($userToken);
            $id_encryption = Utils::encode($uid);

            $tokenCookieName = Utils::encode("userToken");
            $IdCookieName = Utils::encode("userID");

            Cookie::set($tokenCookieName, $token_encryption, $expireTime);
            Cookie::set($IdCookieName, $id_encryption, $expireTime);
        }

    }

    /**
     * This method checks if an email address is already in use
     * @param string $email
     * @return bool
     */
    public function emailExists (string $email): bool
    {
        $sql = "SELECT * FROM " . T_USERS . " WHERE email = ?";
        $stmt = Database::getConnection()->prepare($sql);
        $email = Utils::test_input($email);
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $stmt->store_result();
        return $stmt->num_rows > 0;
    }

    /**
     * This method creates a new user
     * @param array $data
     * @return bool
     */
    public function create (array $data): bool
    {
        $uid = Utils::createID('EDS');
        $first_name = Utils::test_input(Utils::secureInput($data['first_name']));
        $last_name = Utils::test_input(Utils::secureInput($data['last_name']));
        $email = Utils::test_input($data['email']);
        $password = Utils::passwordEncryption($data['password']);
        $sql = "INSERT INTO " . T_USERS . " (user_id, first_name, last_name, email, password) VALUES (?, ?, ?, ?, ?)";
        $stmt = Database::getConnection()->prepare($sql);
        $stmt->bind_param('sssss', $uid, $first_name, $last_name, $email, $password);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            $this->setUserSession($uid, false);
            return true;
        }
        return false;
    }

    /**
     * This method fetches a user by user_id
     * @param string $user_id
     * @return array
     */
    public function fetchUser (string $user_id): array
    {
        $sql = "SELECT * FROM " . T_USERS . " WHERE user_id = ? OR email = ?";
        $stmt = Database::getConnection()->prepare($sql);
        $user_id = Utils::test_input($user_id);
        $stmt->bind_param('ss', $user_id, $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();

        $data = [];
      if (!empty($row)) {
          $data['id'] = $row['user_id'];
          $data['first_name'] = Utils::test_output($row['first_name']);
          $data['last_name'] = Utils::test_output($row['last_name']);
          $data['email'] = Utils::test_output($row['email']);
          $data['name'] = $data['first_name'] . ' ' . $data['last_name'];
          $data['initials'] = Utils::generateInitials($data['name']);

          Utils::getTimeStamps(EdusognoApp::config('date_style'), $row, $data);
      }
        return $data;
    }

    /**
     * This method updates a user's password by email or user_id
     * @param string $password
     * @param string $uid
     * @return bool
     */
    public function updatePassword (string $password, string $uid): bool
    {
        $password = Utils::passwordEncryption($password);
        $sql = "UPDATE " . T_USERS . " SET password = ? WHERE user_id = ? OR email = ?";
        $stmt = Database::getConnection()->prepare($sql);
        $stmt->bind_param('sss', $password, $uid, $uid);
        $stmt->execute();
        $stmt->store_result();
        return $stmt->affected_rows > 0;
    }

    /**
     * This method returns total users as an integer
     * @return int
     */
    public function totalUsers (): int
    {
        $sql = "SELECT * FROM " . T_USERS;
        $stmt = Database::getConnection()->prepare($sql);
        $stmt->execute();
        $stmt->store_result();
        return $stmt->num_rows;
    }

    /**
     * This method returns all users as an array
     * @return array
     */
    public function allUsers (): array
    {
        $sql = "SELECT * FROM " . T_USERS;
        $stmt = Database::getConnection()->prepare($sql);
        $stmt->execute();
        $result = $stmt->get_result();

        $data = [];
        while ($row = $result->fetch_assoc()) {
            $entry = [
                'id' => $row['user_id'],
                'first_name' => Utils::test_output($row['first_name']),
                'last_name' => Utils::test_output($row['last_name']),
                'email' => Utils::test_output($row['email']),
                'name' => Utils::test_output($row['first_name']) . ' ' . Utils::test_output($row['last_name']),
                'initials' => Utils::generateInitials(Utils::test_output($row['first_name']) . ' ' . Utils::test_output($row['last_name'])),
            ];

            Utils::getTimeStamps(EdusognoApp::config('date_style'), $row, $entry);

            $data[] = $entry;
        }

        return $data;
    }
    /**
     * This method deletes a user
     * @param string $user_id
     * @return bool
     */
    public function delete (string $user_id): bool
{
        $sql = "DELETE FROM " . T_USERS . " WHERE user_id = ?";
        $stmt = Database::getConnection()->prepare($sql);
        $stmt->bind_param('s', $user_id);
        $stmt->execute();
        $stmt->store_result();
        return $stmt->affected_rows > 0;
    }
}
