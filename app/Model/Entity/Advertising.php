<?php
namespace App\Model\Entity;
require_once(__DIR__."/../../Core/Database.php");

use \App\Core\Database;
use Exception;
use PDOException;

class Advertising {
    public int $id;
    public int $property;
    public int $user;
    public string $title;
    public string $description;
    public string $imagePath;
    public float $businessValue;
    public string $publiDate;
    public int $businessType;
    public int $businessStatus;



    public function insert() {
        try {
            $success = (new Database("advertising"))->insert([
                "property" => $this->property,
                "user" => $this->user,
                "title" => $this->title,
                "description" => $this->description,
                "imagePath" => $this->imagePath,
                "businessValue" => $this->businessValue,
                "publiDate" => $this->publiDate,
                "businessType" => $this->businessType,
                "businessStatus" => $this->businessStatus
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
            if($advertisingObjectsList = (new Database("advertising"))->select(["*"], "", [], "ORDER BY publiDate DESC")) {

                return [
                    "success" => true,
                    "message" => "",
                    "value" => $advertisingObjectsList,
                ];
            }

            return [
                "success" => false,
                "message" => "Falha ao encontrar anúncios.",
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
            $success = (new Database("advertising"))->delete("id=?", [$this->id]);

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
            $result = (new Database("advertising"))->update($id, $columns);
            
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
            if ($advertisingObjectsList = (new Database("advertising"))
                ->select(["*"], "id=?", [$id])) {
                
                return [
                    "success" => true,
                    "value" => $advertisingObjectsList,
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
            if($result = (new Database("advertising"))->select(["*"], "$field=?", [$value])) {
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