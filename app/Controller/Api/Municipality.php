<?php

namespace App\Controller\Api;


require_once(__DIR__ . "/../../Model/Entity/Municipality.php");


use \PDO;
use \App\Model\Entity\Municipality as MunicipalityEntity;
use Exception;



class Municipality
{

    public static function getByState($request, $state){

        try {


            if(!preg_match("/^[0-9]{2}$/", $state)){
                print json_encode([
                    "success" => false, 
                    "message" => "Problema ao carregar municípios"
                ]);
            }

            $result = MunicipalityEntity::getByState($state);

            if (!$result["success"]) {
                return  json_encode([
                    "success" => false,
                    "message" => "Não foi possível carregar os municípios."
                ]);
            }


            $municipalitiesObjectsList = $result["value"];

            $municipalitiesList = $municipalitiesObjectsList->fetchAll(PDO::FETCH_ASSOC);


            if (empty($municipalitiesList)) {
              return json_encode([
                "success" => false, 
                "message" => "Problema ao carregar municípios."
              ]);
            }


            return json_encode([
                "success" => true, 
                "message" => "Municípios carregados", 
                "data" => $municipalitiesList
            ]);
            

        } catch (Exception $e){
            return json_encode([
                "success" => false, 
                "message" => "Problema ao carregar municípios."
              ]);
        }

}



}
