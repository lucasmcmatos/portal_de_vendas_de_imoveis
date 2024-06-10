<?php

namespace App\Model\Entity;

require_once(__DIR__ . "/../../Core/Database.php");

use \App\Core\Database;
use Exception;
use PDOException;

class User
{
    public $id;
    public $username;
    public $fullname;
    public $pass;
    public $cpf;
    public $email;
    public $photo;
    public $date;
    public $privilege;



    public function insertUser(){
        try {
            $success = (new Database("user"))->insert([
                "username" => $this->username,
                "fullname" => $this->fullname,
                "pass" => $this->pass,
                "cpf" => $this->cpf,
                "email" => $this->email,
                "photo" => $this->photo,
                "date" => $this->date,
                "privilege" => 0,
            ]);

            return [
                "success" => $success,
                "message" => ""
            ];
        } catch (PDOException $err) {
            return [
                "success" => false,
                "message" => $err->getMessage(),
            ];
        }
    }



    public function deleteUser(){
        try {
            $success = (new Database("user"))->delete("id=?", [$this->id]);

            return [
                "success" => $success,
                "message" => ""
            ];
        } catch (PDOException $err) {
            return [
                "success" => false,
                "message" => $err->getMessage(),
            ];
        }
    }



    static public function getUsers(){
        try {

            if($objUsers = (new Database("user"))->select()) {
                return [
                    "success" => true,
                    "message" => "",
                    "value" => $objUsers,
                ];

            }

            return [
                "success" => false,
                "message" => "Falha ao encontrar usuários.",
            ];

        } catch (PDOException $err) {
            return [
                "success" => false,
                "message" => $err->getMessage(),
            ];
        }
    }


    static public function getUserByIdentifier($value){
        try {
            if($objUser = (new Database("user"))
                ->select(["*"], "email=? OR username=?", [$value, $value])){
                return [
                    "success" => true,
                    "message" => "",
                    "value" => $objUser->fetchObject(self::class),
                ];
            }
            
            return [
                "success" => false,
                "message" => "Falha ao encontrar usuário.",
            ];
            
        } catch (PDOException $err) {
            return [
                "success" => false,
                "message" => $err->getMessage(),
            ];
        }
    }



    static public function getUserById($id){
        try {
            if ($objUser = (new Database("user"))
                ->select(["*"], "id=?", [$id])) {

                return [
                    "success" => true,
                    "value" => $objUser->fetchObject(self::class),
                    "message"  =>  "",
                ];
            }

            return [
                "success" => false,
                "message"  =>  "Falha ao encontrar usuário.",
            ];

        } catch (PDOException $err) {
            return [
                "success" => false,
                "message" => $err->getMessage(),
            ];
        }
    }



    public static function updateFields($id, $columns){
        try {
            $result = (new Database("user"))->update($id, $columns);
            return [
                "success" => $result,
                "message" => "",
            ];
        } catch (PDOException $err) {
            return [
                "success" => false,
                "message" => $err->getMessage(),
            ];
        }
    }



    public static function updatePassword($id, $newPass){
        try {
            $result = (new Database("user"))->update($id, [
                "pass" => $newPass,
            ]);

            return [
                "success" => $result,
                "message" => "",
            ];
        } catch (PDOException $err) {
            return [
                "success" => false,
                "message" => $err->getMessage(),
            ];
        }
    }



    public static function isUnique($field, $value){
        try {
            if($result = (new Database("user"))->select(["*"], "$field=?", [$value])) {
                return [
                    "success" => true,
                    "value" => $result->rowCount() == 0,
                    "message" => "",
                ];
            }

            return [
                "success" => false,
                "message" => "",
            ];
        } catch (PDOException $err) {
            return [
                "success" => false,
                "message" => $err->getMessage(),
            ];
        }
    }
}
