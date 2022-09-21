<?php

class Database {

    public static $host = 'localhost';
    public static $dbname = 'robot.skalnyy.com';
    public static $user = 'robot.skalnyy.com';
    public static $pass = 'HnYPEc5pXmSPbHLc';

    private static function connect() {
        $pdo = new PDO("mysql:host=".self::$host.";dbname=".self::$dbname.";charset=utf8", self::$user, self::$pass, array(PDO::MYSQL_ATTR_LOCAL_INFILE => true,));
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $pdo;
    }
    
    public static function db(){
        return self::connect();
    }

    public static function query($query, $param = array()) {
        try {
            if(explode(' ', $query)[0] == 'SELECT'){
                $query = explode(';', $query)[0];
                $statement = self::connect()->prepare($query);
                foreach ($param as $key => $par){
                    $statement->bindValue($key, $par);
                }
                $statement->execute();
                $data = $statement->fetchAll();
                
                return $data;
            }
        } catch (Exception $e){
            return [];
        }
        
    }
    
    public static function execute($query, $param = array()) {
        try {
            $statement = self::connect()->prepare($query);
            foreach ($param as $key => $par){
                $statement->bindValue($key, $par);
            }
            
            $statement->execute();
            return true;
        } catch (Exception $e){
            print($e);
            return false;
        }
        
    }
    
    public static function prepare($query) {
        $statement = self::connect()->prepare($query);
        return $statement;
    }
    public static function bindParam($Param, $Value) {
        $statement = self::connect()->bindParam($Param, $Value);
    }
}