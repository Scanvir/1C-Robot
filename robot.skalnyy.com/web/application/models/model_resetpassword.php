<?php

class Model_ResetPassword extends Model
{

    private $db;

    function __construct()
    {
        $this->db = new Database();
    }

    function generateHash($password) {
        if (defined("CRYPT_BLOWFISH") && CRYPT_BLOWFISH) {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            return $hash;
        }
    }

    public function resetUserPassword($userId, $reset_hash)
    {
        $query = "UPDATE users SET reset_hash = :reset_hash, reset_expires = DATE_ADD(NOW(), INTERVAL 2 HOUR), updated_at = now() WHERE id = :userId";
        $stmt = $this->db->execute($query, [':reset_hash' => $reset_hash, ':userId' => $userId]);

        return $stmt;
    }

    public function checkUserEmail($email)
    {
        $query = "SELECT personName, id FROM users WHERE email = :email";
        $stmt = $this->db->query($query, [':email' => $email]);

        return $stmt;
    }

    public function checkResetHash($reset_hash)
    {
        $query = "SELECT id FROM users WHERE reset_hash = :reset_hash and reset_expires > Now()";
        $stmt = $this->db->query($query, [':reset_hash' => $reset_hash]);

        return $stmt;
    }

    public function checkPassword($password)
    {
        if (strlen($password) >= 8) return true;
        else return false;
    }

    public function checkEmail($email)
    {
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) return true;
        else return false;
    }

    public function updateUserPassword($userId, $password_hash)
    {
        $query = "UPDATE users SET password_hash = :password_hash, reset_hash = '', reset_expires = NOW() WHERE id = :userId";
        $stmt = $this->db->execute($query, [':password_hash' => $password_hash, ':userId' => $userId]);

        return $stmt;
    }
}