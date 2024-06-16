<?php
namespace App\Model\Entity;
require_once(__DIR__."/../../Core/Database.php");

use \App\Core\Database;
use Exception;
use PDOException;

class BusinessStatus {
    public int $id;
    public string $status;

    public function insert() {
        try {
            $success = (new Database("business_status"))->insert([
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
            if($businessStatusObjectsList = (new Database("business_status"))->select(["*"], "", [])) {

                return [
                    "success" => true,
                    "message" => "",
                    "value" => $businessStatusObjectsList,
                ];
            }

            return [
                "success" => false,
                "message" => "Falha ao encontrar status da negociação.",
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
            $success = (new Database("business_status"))->delete("id=?", [$this->id]);

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
            $result = (new Database("business_status"))->update($id, $columns);
            
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
            if ($businessStatusObjectsList = (new Database("business_status"))
                ->select(["*"], "id=?", [$id])) {
                
                return [
                    "success" => true,
                    "value" => $businessStatusObjectsList,
                    "message"  =>  "",
                ];
            }

            return [
                "success" => false,
                "message"  =>  "Falha ao encontrar status da negociação.",
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
            if($result = (new Database("business_status"))->select(["*"], "$field=?", [$value])) {
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