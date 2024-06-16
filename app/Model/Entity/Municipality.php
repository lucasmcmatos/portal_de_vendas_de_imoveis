<?php
namespace App\Model\Entity;
require_once(__DIR__."/../../Core/Database.php");

use \App\Core\Database;
use Exception;
use PDOException;

class Municipality {
    public int $id;
    public string $code;
    public string $state;
    public string $name;



    public function insert() {
        try {
            $success = (new Database("municipality"))->insert([
                "code" => $this->code,
                "state" => $this->state,
                "name" => $this->name
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
            if($municipalityObjectsList = (new Database("municipality"))->select(["*"], "", [])) {

                return [
                    "success" => true,
                    "message" => "",
                    "value" => $municipalityObjectsList,
                ];
            }

            return [
                "success" => false,
                "message" => "Falha ao encontrar município.",
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
            $success = (new Database("municipality"))->delete("id=?", [$this->id]);

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
            $result = (new Database("municipality"))->update($id, $columns);
            
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
            if ($municipalityObjectsList = (new Database("municipality"))
                ->select(["*"], "id=?", [$id])) {
                
                return [
                    "success" => true,
                    "value" => $municipalityObjectsList,
                    "message"  =>  "",
                ];
            }

            return [
                "success" => false,
                "message"  =>  "Falha ao encontrar anúncio.",
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
            if($result = (new Database("municipality"))->select(["*"], "$field=?", [$value])) {
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