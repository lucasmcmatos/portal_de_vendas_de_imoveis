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
            $pdo = (new Database("advertising"))->insert([
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

            if($pdo == false){
                return [
                    "success" => false,
                    "message" => "Falha ao cadastrar anúncio",
                ];
            }

            return [
                "success" => true,
                "pdo" => $pdo,
                "message" => "Anúncio cadastrado com sucesso!"
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



    static public function getOpenAdvertisingInformationsById($id){

        try {

            $columns = [
                "advertising.id as advertisingId",
                "title", 
                "description",
                "imagePath",
                "businessValue",
                "publiDate",
                "business_type.type as businessTypeName",
                "business_status.status as businessStatusName",
                "builtUpArea",
                "bathNumber",
                "roomNumber",
                "parkingSpaces",
                "property_type.type as propertyTypeName",
                "incidence",
                "furnished",
                "pool",
                "barbecue",
                "sportsCourt",
                "municipality.name as municipality",
                "neighborhood",
                "street",
                "number",
                "ZIPcode",
                "complement",
                "state.name as state",
                "firstName",
                "lastName",
                "email",
                "phone",
                "instagram",
                "whatsapp"
            ];


            if($advertisingObjectsList = (
                new Database("advertising"))->select(
                $columns, 
                "advertising.id=?", 
                [$id], 
                "",
                "JOIN business_type ON advertising.businessType = business_type.id
                JOIN business_status ON advertising.businessStatus = business_status.id
                JOIN user ON advertising.user = user.id
                JOIN property ON advertising.property = property.id
                JOIN property_type ON property.propertyType = property_type.id
                JOIN solar_incidence ON property.solarIncidence = solar_incidence.id
                JOIN address ON property.address = address.id
                JOIN municipality ON address.municipality = municipality.code
                JOIN state ON municipality.state = state.code"

                )
            ) {

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




    


    static public function getPlatformAdvertisingsCardInformation() {
         try {

            $columns = [
                "advertising.id as id",
                "title", 
                "description",
                "imagePath",
                "businessValue",
                "street",
                "number",
                "municipality.name as municipality",
                "state.name as state"
            ];


            if($advertisingObjectsList = (
                new Database("advertising"))->select($columns, 
                "", 
                [], 
                "ORDER BY publiDate DESC",

                "JOIN property ON advertising.property = property.id
                JOIN address ON property.address = address.id
                JOIN municipality ON address.municipality = municipality.code
                JOIN state ON municipality.state = state.code"

                )
            ) {

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