<?php

namespace App\Controller\Api;


require_once(__DIR__ . "/../../Model/Entity/PropertyType.php");


use \PDO;
use \App\Model\Entity\PropertyType as PropertyTypeEntity;
use Exception;



class PropertyType
{

    public static function get(){

        try {

            $result = PropertyTypeEntity::get();

            if (!$result["success"]) {
                return  json_encode([
                    "success" => false,
                    "message" => "Não foi possível carregar os tipos de propriedade."
                ]);
            }


            $propertyTypeObjectsList = $result["value"];

            $propertyTypeList = $propertyTypeObjectsList->fetchAll(PDO::FETCH_ASSOC);


            if (empty($propertyTypeList)) {
              return json_encode([
                "success" => false, 
                "message" => "Problema ao carregar tipos de propriedade."
              ]);
            }


            return json_encode([
                "success" => true, 
                "message" => "Tipos de propriedade carregados", 
                "data" => $propertyTypeList
            ]);
            

        } catch (Exception $e){
            return json_encode([
                "success" => false, 
                "message" => "Problema ao carregar tipos de propriedade."
              ]);
        }

}



}
