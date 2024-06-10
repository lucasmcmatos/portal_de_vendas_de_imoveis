<?php
namespace App\Core;
use PDO;
use PDOException;

class Database {
    static private $dbHost;
    static private $dbName;
    static private $dbUser;
    static private $dbPass;
    static private $dbPort;
    private $tableName = "";
    private $conn;

    public function __construct($tableName) {
        $this->tableName = $tableName;
        $this->conn = new PDO("mysql:host=".static::$dbHost.";port=".static::$dbPort.";dbname=".static::$dbName, static::$dbUser, static::$dbPass);
    }

    public static function config($dbHost, $dbName, $dbUser, $dbPass, $dbPort = 3306) {
        static::$dbHost = $dbHost;
        static::$dbName = $dbName;
        static::$dbUser = $dbUser;
        static::$dbPass = $dbPass;
        static::$dbPort = $dbPort;
    }

    public function select($columns = ["*"], $condition = "", $values = [], $order = "") {

        try {
            $columns = join(", ", $columns);
            $query = "SELECT $columns FROM $this->tableName";
    
            if ($condition) {
                $query .= " WHERE $condition $order";
                $stmt = $this->conn->prepare($query);
    
                if ($stmt->execute($values)) {
                    return $stmt;
                }

            } else {
                $query .= "  $order";
            }
            
            return $stmt = $this->conn->query($query);

        } catch (PDOException $err) {
            return false;
        }

    }




    public function insert($columns) {
        try{
            $values = array_values($columns);
            $keys = join(", ", array_keys($columns));
            $valuesField = join(", ",  array_fill(0, count($columns), "?"));
            $query = "INSERT INTO $this->tableName ($keys) VALUES ($valuesField)";
    
            $stmt = $this->conn->prepare($query);
            return $stmt->execute($values);

        } catch(PDOException $err){
            return false;  
        }
    }




    public function delete($condition, $values = []) {

        try {
            if (!$condition) {
                return false;
            }
    
            $query = "DELETE FROM $this->tableName WHERE ". $condition ;
            $stmt = $this->conn->prepare($query);
            $lines = $stmt->execute($values);
    
            return boolval($lines > 0); 

        } catch(PDOException $err){
            return false;
        }
    }



    public function update($id, $columns) {
        try {
            $keys = array_keys($columns);
            $keys = array_map(function ($col){
                return "$col = ?";
            },$keys);
            $keys = join(", ", $keys);

            $query = "UPDATE $this->tableName SET $keys WHERE id=?";
            $stmt = $this->conn->prepare($query);

            $values = array_values($columns);
            array_push($values, $id);
            return $stmt->execute($values); 
        } catch (PDOException $err) {
            return false;
        }
    }

}


