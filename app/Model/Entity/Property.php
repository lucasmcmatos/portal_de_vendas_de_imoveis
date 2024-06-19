<?php
namespace App\Model\Entity;
require_once(__DIR__."/../../Core/Database.php");

use \App\Core\Database;
use Exception;
use PDOException;


class Property {
    public int $id;
    public float $builtUpArea;
    public int $bathNumber;
    public int $roomNumber;
    public int $parkingSpaces;
    public int $propertyType;
    public int $solarIncidence;
    public int $furnished;
    public int $pool;
    public int $barbecue;
    public int $sportsCourt;
    public int $address;





    public function insert() {
        try {
            $pdo = (new Database("property"))->insert([
                "builtUpArea" => $this->builtUpArea,
                "bathNumber" => $this->bathNumber,
                "roomNumber" => $this->roomNumber,
                "parkingSpaces" => $this->parkingSpaces,
                "propertyType" => $this->propertyType,
                "solarIncidence" => $this->solarIncidence,
                "furnished" => $this->furnished,
                "pool" => $this->pool,
                "barbecue" => $this->barbecue,
                "sportsCourt" => $this->sportsCourt,
                "address" => $this->address,
            ]);

            if($pdo == false){
                return [
                    "success" => false,
                    "message" => "Falha ao cadastrar propriedade",
                ];
            }

            return [
                "success" => true,
                "pdo" => $pdo,
                "message" => "Propriedade cadastrada com sucesso!"
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
            if($propertyObjectsList = (new Database("property"))->select(["*"], "", [])) {

                return [
                    "success" => true,
                    "message" => "",
                    "value" => $propertyObjectsList,
                ];
            }

            return [
                "success" => false,
                "message" => "Falha ao encontrar propriedades.",
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
            $success = (new Database("property"))->delete("id=?", [$this->id]);

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
            $result = (new Database("property"))->update($id, $columns);
            
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
            if ($propertyObjectsList = (new Database("property"))
                ->select(["*"], "id=?", [$id])) {
                
                return [
                    "success" => true,
                    "value" => $propertyObjectsList,
                    "message"  =>  "",
                ];
            }

            return [
                "success" => false,
                "message"  =>  "Falha ao encontrar propriedade.",
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
            if($result = (new Database("property"))->select(["*"], "$field=?", [$value])) {
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