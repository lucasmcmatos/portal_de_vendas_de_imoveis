<?php
namespace App\Model\Entity;
require_once(__DIR__."/../../Core/Database.php");

use \App\Core\Database;
use Exception;
use PDOException;

class PropertyType {
    public int $id;
    public string $type;

    public function insert() {
        try {
            $success = (new Database("property_type"))->insert([
                "type" => $this->type,

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
            if($propertyTypeObjectsList = (new Database("property_type"))->select(["*"], "", [])) {

                return [
                    "success" => true,
                    "message" => "",
                    "value" => $propertyTypeObjectsList,
                ];
            }

            return [
                "success" => false,
                "message" => "Falha ao encontrar tipo da propriedade.",
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
            $success = (new Database("property_type"))->delete("id=?", [$this->id]);

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
            $result = (new Database("property_type"))->update($id, $columns);
            
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
            if ($propertyTypeObjectsList = (new Database("property_type"))
                ->select(["*"], "id=?", [$id])) {
                
                return [
                    "success" => true,
                    "value" => $propertyTypeObjectsList,
                    "message"  =>  "",
                ];
            }

            return [
                "success" => false,
                "message"  =>  "Falha ao encontrar tipo da propriedade.",
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
            if($result = (new Database("property_type"))->select(["*"], "$field=?", [$value])) {
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