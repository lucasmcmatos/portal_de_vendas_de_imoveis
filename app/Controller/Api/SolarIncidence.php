<?php

namespace App\Controller\Api;


require_once(__DIR__ . "/../../Model/Entity/SolarIncidence.php");


use \PDO;
use \App\Model\Entity\SolarIncidence as SolarIncidenceEntity;
use Exception;



class SolarIncidence
{

    public static function get(){

        try {

            $result = SolarIncidenceEntity::get();

            if (!$result["success"]) {
                return  json_encode([
                    "success" => false,
                    "message" => "Não foi possível carregar os tipos de incidência solar."
                ]);
            }


            $solarIncidenceObjectsList = $result["value"];

            $solarIncidenceList = $solarIncidenceObjectsList->fetchAll(PDO::FETCH_ASSOC);


            if (empty($solarIncidenceList)) {
              return json_encode([
                "success" => false, 
                "message" => "Problema ao carregar tipos de incidência solar."
              ]);
            }


            return json_encode([
                "success" => true, 
                "message" => "Tipos de incidência solar carregados", 
                "data" => $solarIncidenceList
            ]);
            

        } catch (Exception $e){
            return json_encode([
                "success" => false, 
                "message" => "Problema ao carregar tipos de incidência solar."
              ]);
        }

}


}
