<?php

namespace App\Model\Entity;

require_once(__DIR__ . "/../../Core/Database.php");

use \App\Core\Database;
use PDOException;



class User
{
    public int $id;
    public string $firstName;
    public string $lastName;
    public string $email;
    public string $birthDate;
    public string $password;
    public string $cpf;
    public string $phone;
    public string $instagram;
    public string $whatsapp;
    public string $registerDate;
    public string $photo;
    public int $privilege;
    public ?int $salaryRange;
    public ?int $maritalStatus;
    public ?int $children;
    public ?int $automobiles;
    public ?int $blocked;
    public ?int $address;



    public function insert(){
        try {
            $success = (new Database("user"))->insert([
                "firstName" => $this->firstName,
                "lastName" => $this->lastName,
                "email" => $this->email,
                "birthDate" => $this->birthDate,
                "password" => $this->password,
                "cpf" => $this->cpf,
                "phone" => $this->phone,
                "instagram" => $this->instagram,
                "whatsapp" => $this->whatsapp,
                "registerDate" => $this->registerDate,
                "photo" => $this->photo,
                "privilege" => $this->privilege,
            ]);

            return [
                "success" => $success,
                "message" => "Usuário cadastrado com sucesso!"
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

            if($UserObjectsList = (new Database("user"))->select()) {
                return [
                    "success" => true,
                    "message" => "",
                    "value" => $UserObjectsList ,
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

            if($UserObjectsList = (new Database("user"))
                ->select(["*"], "email=? OR cpf=?", [$value, $value])){

                    return [
                        "success" => true,
                        "message" => "",
                        "value" => $UserObjectsList
                    ];
            }
            
            return [
                "success" => false,
                "message" => "Falha ao encontrar usuário.",
            ];
            
        } catch (PDOException $error) {
            return [
                "success" => false,
                "message" => $error->getMessage(),
            ];
        }
    }



    static public function getUserById($id){
        try {
            if ($UserObjectsList  = (new Database("user"))
                ->select(["*"], "id=?", [$id])) {

                return [
                    "success" => true,
                    "value" => $UserObjectsList,
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
