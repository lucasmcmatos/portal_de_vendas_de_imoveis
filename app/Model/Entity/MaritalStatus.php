<?php
namespace App\Model\Entity;
require_once(__DIR__."/../../Core/Database.php");

use \App\Core\Database;
use Exception;
use PDOException;

class MaritalStatus {
    public int $id;
    public string $status;

    public function insert() {
        try {
            $success = (new Database("marital_status"))->insert([
                "status" => $this->status,

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


    static public function get() {

        try {
            if($maritalStatusObjectsList = (new Database("marital_status"))->select(["*"], "", [])) {

                return [
                    "success" => true,
                    "message" => "",
                    "value" => $maritalStatusObjectsList,
                ];
            }

            return [
                "success" => false,
                "message" => "Falha ao encontrar estado civil.",
            ];

        } catch (PDOException $err) {
            return [
                "success" => false,
                "message" => $err->getMessage(),
            ];
        }
    }



    public function delete() {
        try {
            $success = (new Database("maritak_status"))->delete("id=?", [$this->id]);

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



    public static function update($id, $columns) {

        try {
            $result = (new Database("marital_status"))->update($id, $columns);
            
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



    static public function getById($id) {
        try {
            if ($businessTypeObjectsList = (new Database("marital_status"))
                ->select(["*"], "id=?", [$id])) {
                
                return [
                    "success" => true,
                    "value" => $businessTypeObjectsList,
                    "message"  =>  "",
                ];
            }

            return [
                "success" => false,
                "message"  =>  "Falha ao encontrar estado civil.",
            ];

        } catch (PDOException $err) {
            return [
                "success" => false,
                "message" => $err->getMessage(),
            ];
        }
    }



    public static function isUnique($field, $value) {

        try {
            if($result = (new Database("marital_status"))->select(["*"], "$field=?", [$value])) {
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