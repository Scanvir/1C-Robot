<?php

class Model_Login extends Model
{

    private $db;

    function __construct()
    {
        $this->db = new Database();
    }

    public function resetUserPassword($userId, $reset_hash) {
        $query = "UPDATE users SET reset_hash = :reset_hash, reset_expires = DATE_ADD(NOW(), INTERVAL 2 HOUR), updated_at = now() WHERE id = :userId";
        $stmt = $this->db->execute($query, [':reset_hash' => $reset_hash, ':userId' => $userId]);

        return $stmt;
    }
    
    public function checkResetHash($reset_hash) {
        $query = "SELECT id FROM users WHERE reset_hash = :reset_hash and reset_expires > Now()";
        $stmt = $this->db->query($query, [':reset_hash' => $reset_hash]);

        return $stmt;
    }
    public function checkPassword($password) {
        if (preg_match('/(?=.*[0-9])(?=.*[a-z])(?=.*[A-Z])[0-9a-zA-Z!@#$%^&*.]{8,}/',$password) == 1) return true;
        else return false;
    }
    public function checkEmail($email) {
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) return true;
        else return false;
    }
    public function checkName($name) {
        if (strlen($name) >= 4) return true;
        else return false;
    }
    public function checkActivateHash($activate_hash) {
        $query = "SELECT id FROM users WHERE activate_hash = :activate_hash";
        $stmt = $this->db->query($query, [':activate_hash' => $activate_hash]);

        return $stmt;
    }
    public function activate($activate_hash) {
        $query = "UPDATE users SET Active = 1, activate_hash = '', updated_at = now() WHERE activate_hash = :activate_hash";
        $stmt = $this->db->execute($query, [':activate_hash' => $activate_hash]);

        return $stmt;
    }
    public function register($personName, $email, $password_hash, $reset_hash) {
        $query = "INSERT INTO users (email, personName, password_hash, activate_hash, created_at, needEmail) VALUES (:email, :personName, :password_hash, :reset_hash, Now(), 0)";

        $stmt = $this->db->execute($query, [':email' => $email, ":personName" => $personName, ':password_hash' => $password_hash, ':reset_hash' => $reset_hash]);
    }
}