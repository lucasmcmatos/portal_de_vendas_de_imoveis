<?php

namespace App\Controller\Api;


require_once(__DIR__ . "/../../Model/Entity/State.php");

use \PDO;
use \App\Model\Entity\State as StateEntity;
use Exception;

class State
{

    public static function get(){

        try {

            $result = StateEntity::get();

            if (!$result["success"]) {
                return  json_encode([
                    "success" => false,
                    "message" => "Não foi possível carregar os estados."
                ]);
            }


            $statesObjectsList = $result["value"];

            $statesList = $statesObjectsList->fetchAll(PDO::FETCH_ASSOC);


            if (empty($statesList)) {
              return json_encode([
                "success" => false, 
                "message" => "Problema ao carregar estados."
              ]);
            }


            return json_encode([
                "success" => true, 
                "message" => "Estados carregados", 
                "data" => $statesList
            ]);
            

        } catch (Exception $e){
            return json_encode([
                "success" => false, 
                "message" => "Problema ao carregar estados."
              ]);
        }

}



}
