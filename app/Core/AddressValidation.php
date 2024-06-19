<?php
namespace App\Core;

require_once(__DIR__."/../Model/Entity/Address.php");
require_once(__DIR__."/../Model/Entity/State.php");
require_once(__DIR__."/../Model/Entity/Municipality.php");

use \App\Model\Entity\Address as AddressEntity;
use \App\Model\Entity\State as StateEntity;
use \App\Model\Entity\Municipality as MunicipalityEntity;
use DateTime;
use \PDO;



class AddressValidation {


    static $validateMap = [

        "municipality" => [
            "label" => "município",
            "selector" => "municipality",
            "validate" => "\App\Core\AddressValidation::municipality",
            "required" => true,
            "unique" => false,
        ],

        "street" => [
            "label" => "logradouro",
            "selector" => "street",
            "validate" => "\App\Core\AddressValidation::street",
            "required" => true,
            "unique" => false,
        ],

        "number" => [
            "label" => "número residencial",
            "selector" => "number",
            "validate" => "\App\Core\AddressValidation::number",
            "required" => false,
            "unique" => false,
        ],

        "neighborhood" => [
            "label" => "bairro",
            "selector" => "neighborhood",
            "validate" => "\App\Core\AddressValidation::neighborhood",
            "required" => true,
            "unique" => false,
        ],


        "complement" => [
            "label" => "complemento",
            "selector" => "complement",
            "validate" => "\App\Core\AddressValidation::complement",
            "required" => false,
            "unique" => false,
        ],


        "ZIPcode" => [
            "label" => "CEP",
            "selector" => "ZIP-code",
            "validate" => "\App\Core\AddressValidation::cep",
            "required" => false,
            "unique" => false,
        ],

    ];


    private static function municipality($municipality){

        $result = MunicipalityEntity::getCountByMunicipalityCode($municipality);

        if(!$result["success"]){
            return [
                "success" => false, 
                "message" => "Erro ao validar <strong>município<strong>."
            ];
        }

        $municipalityObjectsList = $result["value"];


        $municipaltiesQuantity = intval($municipalityObjectsList->fetch(PDO::FETCH_ASSOC)["quantity"]);

        if($municipaltiesQuantity !== 1){
            return [
                "success" => false, 
                "message" => "<strong>Município<strong> inválido."
            ];
        }
        return [
            "success" => true, 
            "message" => "<strong>Município <strong> válido."
        ];
    }



    private static function street($street){

        $streetRegex = "/[\\\\<>\"]|[^\sa-zA-Z0-9.,-\/ª°ºàáâãéêíóôõúÀÁÂÃÉÊÍÓÔÕÚçÇ']|^[0-9]+$|\s{2,}|'{4,}|\\.{2,}|,{2,}|-{2,}|\/{2,}|ª{2,}|º{2,}|°{2,}|^'+|^\\.+|^,+|^-+|^\/+|^ª+|^º+|^°+/";

        $street = trim($street);
        if (preg_match($streetRegex, $street) || strlen($street) > 255) {
            return [
                "success" => false, 
                "message" => "<strong>Logradouro<strong> inválido."
            ];
        }

        return [
            "success" => true, 
            "message" => "<strong>Logradouro<strong> válido."
        ];

    }



    private static function number($number){
        
        $firstNumberRegex = "/^[0-9]{1,5}$/";
        $secondNumberRegex = "/^0{1,5}$/";
    
        if (!preg_match($firstNumberRegex, trim($number)) || preg_match($secondNumberRegex, trim($number)) || strlen($number) > 5) {
            return [
                "success" => false, 
                "message" => "<strong>Número residencial<strong> inválido."
            ];
        }
    
        return [
            "success" => true, 
            "message" => "<strong>Número residencial<strong> válido."
        ];
    
    }



    private static function neighborhood($neighborhood){
        $neighborhoodRegex = "/[\\\\<>\"]|[^\sa-zA-Z0-9.,-\/ª°ºàáâãéêíóôõúÀÁÂÃÉÊÍÓÔÕÚçÇ']|^[0-9]+$|\s{2,}|'{4,}|\\.{2,}|,{2,}|-{2,}|\/{2,}|ª{2,}|º{2,}|°{2,}|^'+|^\\.+|^,+|^-+|^\/+|^ª+|^º+|^°+/";

        $neighborhood = trim($neighborhood);
        if (preg_match($neighborhoodRegex, $neighborhood) || strlen($neighborhood) > 255) {
            return [
                "success" => false, 
                "message" => "<strong>Bairro<strong> inválido."
            ];
        }

        return [
            "success" => true, 
            "message" => "<strong>Bairro<strong> válido."
        ];

    }



    private static function complement($complement){

        $complementRegex = "/[\\\\<>\"]|[^\sa-zA-Z0-9.,-\/ª°ºàáâãéêíóôõúÀÁÂÃÉÊÍÓÔÕÚçÇ']|^[0-9]+$|\s{2,}|'{4,}|\\.{2,}|,{2,}|-{2,}|\/{2,}|ª{2,}|º{2,}|°{2,}|^'+|^\\.+|^,+|^-+|^\/+|^ª+|^º+|^°+/";

        $complement = trim($complement);
        if (preg_match($complementRegex, $complement) || strlen($complement) > 255) {
            return [
                "success" => false, 
                "message" => "<strong>Complemento<strong> inválido."
            ];
        }

        return [
            "success" => true, 
            "message" => "<strong>Complemento<strong> válido."
        ];

    }



    private static function cep($cep) {
        $cepRegex = "/^[0-9]{8}$/";

        if (!preg_match($cepRegex, $cep)) {
            return ["success" => false, "message" => "<strong>CEP<strong> inválido."];
        }

        $response = file_get_contents("https://viacep.com.br/ws/$cep/json/");
        $placeObject = json_decode($response);

        if(isset($placeObject->erro)){
            return [
                "success" => false, 
                "message" => "Erro ao validar <strong>CEP<strong>. Por favor, tente novamente."
            ];
        }
            
        return [
            "success" => true, 
            "message" => "<strong>CEP<strong> válido."
        ];
    }












    public static function validateForm($form) {
        foreach ($form as $key => $value) {
            $settings = self::$validateMap[$key];

            if (empty($value)) {
                if ($settings["required"]) {
                    return ["success" => false, "message" => "O campo <strong>" . $settings["label"] . "</strong> é obrigatório." ];
                }

                continue;
            }

            

            if ($settings["unique"]) {
                $isUnique = AddressEntity::isUnique($key, $value);
                
                if (!$isUnique["success"]) {
                    return [
                        "success" => false,
                        "message" => "Erro na conexão. Tente novamente." ,
                    ];
                }

                if (!$isUnique["value"]) {
                    return ["success" => false, "message" =>  "Valor de <strong>".$settings["label"]."</strong> já cadastrado."];
                }
            }

            $result = $settings["validate"]($value);
            if ($result["success"] == false) {
                return $result; 
            }
        }

        return ["success" => true, "message" => "Campos válidos"];
    }



}





