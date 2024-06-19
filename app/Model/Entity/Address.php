<?php
namespace App\Model\Entity;
require_once(__DIR__."/../../Core/Database.php");

use \App\Core\Database;
use Exception;
use PDOException;

class Address {
    public int $id;
    public int $municipality;
    public string $neighborhood;
    public string $street;
    public string $number;
    public string $ZIPcode;
    public string $complement;




    public function insert() {
        try {
            $pdo = (new Database("address"))->insert([
                "municipality" => $this->municipality,
                "neighborhood" => $this->neighborhood,
                "street" => $this->street,
                "number" => $this->number,
                "ZIPcode" => $this->ZIPcode,
                "complement" => $this->complement
            ]);


            if($pdo == false){
                return [
                    "success" => false,
                    "message" => "Falha ao cadastrar endereço",
                ];
            }

            return [
                "success" => true,
                "pdo" => $pdo,
                "message" => "Endereço cadastrado com sucesso!"
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
            if($addressObjectsList = (new Database("address"))->select(["*"], "", [])) {

                return [
                    "success" => true,
                    "message" => "",
                    "value" => $addressObjectsList,
                ];
            }

            return [
                "success" => false,
                "message" => "Falha ao encontrar endereços.",
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
            $success = (new Database("address"))->delete("id=?", [$this->id]);

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
            $result = (new Database("address"))->update($id, $columns);
            
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
            if ($addressObjectsList = (new Database("address"))
                ->select(["*"], "id=?", [$id])) {
                
                return [
                    "success" => true,
                    "value" => $addressObjectsList,
                    "message"  =>  "",
                ];
            }

            return [
                "success" => false,
                "message"  =>  "Falha ao encontrar endereço.",
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
            if($result = (new Database("address"))->select(["*"], "$field=?", [$value])) {
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