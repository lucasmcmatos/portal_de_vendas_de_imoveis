<?php

namespace App\Api;


use \Exception;
use \DateTime;


class General {

        public static function getCurrentDate() {

        try {

            $currentDate = new DateTime();

            return [
                "success" => true,
                "currentDate" => $currentDate->format("Y-m-d\TH:i"),
                "message" => "Data corrente obtida com sucesso."
            ];

        } catch (Exception $e) {
            return [
                "success" => false,
                "currentDate" => "",
                "message" => "Erro ao obter data corrente."
            ];
        }
       
    }
}