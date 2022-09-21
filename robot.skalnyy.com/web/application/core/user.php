<?php

class User
{
    public static function generateHash($password) {
        if (defined("CRYPT_BLOWFISH") && CRYPT_BLOWFISH) {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            return $hash;
        }
    }
    public static function verifyHash($userId, $password, $hash, $error_login = 0) {
        $db = new Database();
        if (password_verify($password, $hash)) {
            $query = "UPDATE users SET error_login = 0, blocked_to = null WHERE id = :id";
            $stmt = $db->execute($query, [':id' => $userId]);
            
            return true;
        } else {
            if($error_login > 0 && ($error_login + 1) % 5 == 0)
                $block = ", blocked_to = DATE_ADD(NOW(), INTERVAL 30 MINUTE)";
            else
                $block = "";
            
            $query = "UPDATE users SET error_login = error_login + 1 ".$block." WHERE id = :id";
            $stmt = $db->execute($query, [':id' => $userId]);
            
            return false;
        }
    }
    public static function isGuest() {
        if (!empty($_COOKIE['userId'])) {
            $userId = (int)$_COOKIE['userId'];
            $db = new Database();
            $query = "SELECT id from users where id = :id";

            $stmt = $db->query($query, [':id' => $userId]);

            if (count($stmt) > 0)
                return true;
                
        }
        return false;
    }
    public static function isAdmin() {
        if (!empty($_COOKIE['userId'])) {
            $userId = (int)$_COOKIE['userId'];
            
            $db = new Database();
            $query = "SELECT id from users where admin = 1 and id = :id";

            $stmt = $db->query($query, [':id' => $userId]);
            
            if (count($stmt) > 0)
                return true;
                
        }
        return false;
    }
    public static function checkName($name) {
        if (strlen($name) >= 2) {
            return true;
        }
        return false;
    }
    public static function checkPassword($password) {
        if (strlen($password) >= 6) {
            return true;
        }
        return false;
    }
    public static function checkEmail($email) {
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return true;
        }
        return false;
    }
    public static function checkUserEmail($email) {
        $db = new Database();
        $query = "SELECT personName, password_hash, id, active, activate_hash, error_login, case when blocked_to > Now() then 1 else 0 end blocked FROM users WHERE email = :email";
        $stmt = $db->query($query, [':email' => $email]);

        return $stmt;
    }
    public static function activeUser($userId) {
        $db = new Database();
        $query = 'UPDATE users set active_at = NOW() where id = :id';

        $db->execute($query, [':id' => $userId]);
    }
    public static function getAllUsers($order = 'personName') {
        $db = new Database();
        $query = 'SELECT * FROM users order by '.$order.' desc';

        return $db->query($query);
    }
    public static function getAllUsersToXLS() {
        $db = new Database();
        $query = 'SELECT * FROM users';
        $stmt = $db->query($query);
        
        foreach ($stmt as $row) {
            $result[] = array(
                'id' => $row['id'],
                'email' => $row['email'],
                'name' => $row['personName'],
                'active' => $row['active'],
                'created_at' => $row['created_at'],
                'active_at' => $row['active_at'],
                'admin' => $row['admin'],
                );
        }
        return $result;
    
    }
    public static function profile($userId) {
        $db = new Database();
        $query = "SELECT email, personName FROM users where id = :id";
        $stmt = $db->query($query, [':id' => $userId]);
        foreach ($stmt as $row) {
            $result= array(
                'email' => $row['email'],
                'personName' => $row['personName'],
                );
        }
        return $result;
    }
    public static function newUsers() {
        $db = new Database();
        $query = "SELECT week(created_at, 1) week, count(1) count FROM users 
            WHERE week(created_at, 1) > week(now()) - 6
            group by week(created_at, 1) 
            order by week(created_at, 1)";
        $stmt = $db->query($query);
        $result = [];
        foreach ($stmt as $row) {
            $result[] = array(
                'week' => $row['week'],
                'count' => $row['count'],
                );
        }
        return $result;
    }
    public static function activeUsers() {
        $db = new Database();
        $query = "SELECT week(active_at, 1) week, count(1) count FROM users 
            where active_at is not null 
            and week(active_at, 1) > week(now()) - 6
            group by week(active_at, 1) 
            order by week(active_at, 1)";
        $stmt = $db->query($query);
        $result = [];
        foreach ($stmt as $row) {
            $result[] = array(
                'week' => $row['week'],
                'count' => $row['count'],
                );
        }
        return $result;
    }
    public static function updateProfile($userId, $personName) {
        $db = new Database();
        $query = "UPDATE users SET personName = :personName where id = :id";
        $stmt = $db->execute($query, [':personName' => $personName, ':id' => $userId]);
        echo $personName;
    }
    public static function updateUserPassword($userId, $password_hash) {
        $db = new Database();
        $query = "UPDATE users SET password_hash = :password_hash, reset_hash = '', reset_expires = NOW() WHERE id = :id";
        $stmt = $db->execute($query, [':password_hash' => $password_hash, ':id' => $userId]);

        return $stmt;
    }
    
    // delete 
    public static function getOld() {
        $db = new Database();
        $query = "SELECT id, email, oldid FROM `users` where oldId > 0";
        $stmt = $db->query($query);
        $result = [];
        foreach ($stmt as $row) {
            $result[] = array(
                'id' => $row['id'],
                'email' => $row['email'],
                'oldid' => $row['oldid'],
                );
        }
        return $result;
    }
    public static function getNoActive() {
         $db = new Database();
        $query = "SELECT id, email, personName, activate_hash FROM `users` where active = 0";
        $stmt = $db->query($query);
        $result = [];
        foreach ($stmt as $row) {
            $result[] = array(
                'id' => $row['id'],
                'email' => $row['email'],
                'personName' => $row['personName'],
                'activate_hash' => $row['activate_hash'],
                );
        }
        //$result[] = ['email' => 'v.skalnyi@fozzy.ua', 'activate_hash' => '22iqo5g1vni84s4k4sowsww8w0g4sc8'];
        return $result;
    }
}