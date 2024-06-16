<?php
namespace App\Model\Entity;
require_once(__DIR__."/../../Core/Database.php");

use \App\Core\Database;
use Exception;
use PDOException;

class SolarIncidence {
    
    public int $id;
    public string $incidence;

    public function insert() {
        try {
            $success = (new Database("solar_incidence"))->insert([
                "incidence" => $this->incidence,

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
            if($solarIncidenceObjectsList = (new Database("solar_incidence"))->select(["*"], "", [])) {

                return [
                    "success" => true,
                    "message" => "",
                    "value" => $solarIncidenceObjectsList,
                ];
            }

            return [
                "success" => false,
                "message" => "Falha ao encontrar incidências solares.",
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
            $success = (new Database("solar_incidence"))->delete("id=?", [$this->id]);

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
            $result = (new Database("solar_incidence"))->update($id, $columns);
            
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
            if ($solarIncidenceObjectsList = (new Database("solar_incidence"))
                ->select(["*"], "id=?", [$id])) {
                
                return [
                    "success" => true,
                    "value" => $solarIncidenceObjectsList,
                    "message"  =>  "",
                ];
            }

            return [
                "success" => false,
                "message"  =>  "Falha ao encontrar incidência solar.",
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
            if($result = (new Database("solar_incidence"))->select(["*"], "$field=?", [$value])) {
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