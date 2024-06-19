<?php

namespace App\Controller\Api;


require_once(__DIR__ . "/../../Model/Entity/BusinessType.php");


use \PDO;
use \App\Model\Entity\BusinessType as BusinessTypeEntity;
use Exception;



class BusinessType
{

    public static function get(){

        try {

            $result = BusinessTypeEntity::get();

            if (!$result["success"]) {
                return  json_encode([
                    "success" => false,
                    "message" => "Não foi possível carregar os tipos de negociação."
                ]);
            }


            $businessTypeObjectsList = $result["value"];

            $businessTypeList = $businessTypeObjectsList->fetchAll(PDO::FETCH_ASSOC);


            if (empty($businessTypeList)) {
              return json_encode([
                "success" => false, 
                "message" => "Problema ao carregar tipos de negociação."
              ]);
            }


            return json_encode([
                "success" => true, 
                "message" => "Tipos de negociação carregados", 
                "data" => $businessTypeList
            ]);
            

        } catch (Exception $e){
            return json_encode([
                "success" => false, 
                "message" => "Problema ao carregar tipos de negociação."
              ]);
        }

}



}
