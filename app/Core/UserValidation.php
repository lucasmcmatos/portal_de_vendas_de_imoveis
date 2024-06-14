<?php
namespace App\Core;

require_once(__DIR__."/../Model/Entity/User.php");

use \App\Model\Entity\User as UserEntity;

class UserValidation {

    static $validateMap = [
        "firstName" => [
            "label" => "nome",
            "selector" => "firstName",
            "validate" => "\App\Core\UserValidation::firstName",
            "required" => true,
            "unique" => false,
        ],

        "lastName" => [
            "label" => "sobrenome",
            "selector" => "lastName",
            "validate" => "\App\Core\UserValidation::lastName",
            "required" => true,
            "unique" => false,
        ],

        "email" => [
            "label" => "email",
            "selector" => "email",
            "validate" => "\App\Core\UserValidation::email",
            "required" => true,
            "unique" => true,
        ],

        "birthDate" => [
            "label" => "data de nascimento",
            "selector" => "birthDate",
            "validate" => "\App\Core\UserValidation::birthDate",
            "required" => true,
            "unique" => false,
        ],

        "password" => [
            "label" => "senha",
            "selector" => "password",
            "validate" => "\App\Core\UserValidation::password",
            "required" => true,
            "unique" => false,
        ],

        "cpf" => [
            "label" => "CPF",
            "selector" => "cpf",
            "validate" => "\App\Core\UserValidation::cpf",
            "required" => true,
            "unique" => true,
        ],

        "phone" => [
            "label" => "telefone",
            "selector" => "phone",
            "validate" => "\App\Core\UserValidation::phone",
            "required" => true,
            "unique" => false,
        ],

        "instagram" => [
            "label" => "instagram",
            "selector" => "instagram",
            "validate" => "\App\Core\UserValidation::instagram",
            "required" => false,
            "unique" => false,
        ],

        "whatsapp" => [
            "label" => "whatsapp",
            "selector" => "whatsapp",
            "validate" => "\App\Core\UserValidation::whatsapp",
            "required" => false,
            "unique" => false,
        ],
 
        "photo" => [
            "label" => "foto",
            "selector" => "photo",
            "validate" => "\App\Core\UserValidation::photo",
            "required" => false,
            "unique" => false,
        ]
    ];

     

    private static function firstName($firstName) {
        $firstNameRegex = "/[^\sa-zA-ZàáâãéêíóôõúÀÁÂÃÉÊÍÓÔÕÚçÇ']|\s{2,}|'{2,}|^'/";
        if (preg_match($firstNameRegex, $firstName) || strlen($firstName) < 3 || strlen($firstName) > 100) {
            return ["success" => false, "message" => "<strong>Nome<strong> inválido."];
        }

        return ["success" => true, "message" => ""];
    }



    private static function lastName($lastName) {
        $lastNameRegex = "/[^\sa-zA-ZàáâãéêíóôõúÀÁÂÃÉÊÍÓÔÕÚçÇ']|\s{2,}|'{2,}|^'/";
        if (preg_match($lastNameRegex, $lastName) || strlen($lastName) < 3 || strlen($lastName) > 155) {
            return ["success" => false, "message" => "<strong>sobrenome<strong> inválido."];
        }

        return ["success" => true, "message" => ""];
    }



    private static function email($email) {
        $emailRegex = "/^[a-zA-Z0-9._-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z]{2,})+$/";

        if (!preg_match($emailRegex, $email)) {
            return ["success" => false, "message" => "<strong>Email</strong> inválido"];
        }

        return ["success" => true, "message" => ""];
    }



    private static function birthDate($birthDate) {


        return ["success" => true, "message" => ""];
    }



    private static function password($password) {
        $passwordRegex = "/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&\.])[A-Za-z\d@$!%*?&\.]{8,}$/";

        if (!preg_match($passwordRegex, $password)) {
            return ["success" => false, "message" => "<strong>Senha</strong> não é forte o suficiente."];
        }

        return ["success" => true, "message" => ""];
    }



    private static function cpf($cpf) {
        if (!self::validateCpf($cpf)) {
            return ["success" => false, "message" => "<strong>CPF</strong> inválido"];
        }

        return ["success" => true, "message" => ""];
    }

        private static function validateCpf($cpf) {
            // Check format 
            if (!preg_match("/[0-9]{11}/", $cpf)) {
                return false;
            }

            // Check Equal numbers 
            if (str_replace($cpf[0], "", $cpf) == "") {
                return false;
            }

            $cpfSequence = substr($cpf, 0, 9);
            $firstDigit = self::calculateCpfDigits($cpfSequence);

            $secondSequence = $cpfSequence . $firstDigit;
            $secondSequence = substr($secondSequence, 1);
            $secondDigit = self::calculateCpfDigits($secondSequence);

            $cpfCheckDigits = substr($cpf, -2);

            return  $cpfCheckDigits == $firstDigit . $secondDigit;
        }

        private static function calculateCpfDigits($parcialCpf) {
            $sum = 0;
            $j = 2;
            for ($i = 8; $i >= 0; $i--) {
                $sum += intval($parcialCpf[$i]) * $j;
                $j++;
            }
            $rest = $sum % 11;
            $digit = ($rest < 2) ? 0 : (11 - $rest);
            return strval($digit);
        }




    private static function phone($phone) {

        return ["success" => true, "message" => ""];
    }



    private static function instagram($instagram) {
        //$alphaRegex = "/^[a-zA-Z0-9_.]{3,255}$/";
        $instagramRegex = "/^(?=.{5,20}$)(?![_.])(?!.*[_.]{2})[a-zA-Z0-9._]+(?<![_.])$/";

        if (!preg_match($instagramRegex, $instagram)) {
            return ["success" => false, "message" => "<strong>Usuário de instagram</strong> inválido."];
        }

        return ["success" => true, "message" => ""];
    }



    private static function whatsapp($whastapp) {

        return ["success" => true, "message" => ""];
    }



    private static function photo($tmpName) {
        $validTypes = ["image/jpeg", "image/png", "image/webp", "image/svg+xml"];

        if (!file_exists($tmpName)) {
            return ["success" => false, "message" => "Problema ao salvar <strong>foto de perfil</strong>."];
        }

        if (filesize($tmpName) > 5000000) {
            return ["success" => false, "message" => "Tamanho máximo de 5MB excedido para <strong>foto de perfil</strong>."];
        }

        if (!in_array(mime_content_type($tmpName), $validTypes)) {
            return ["success" => false, "message" => "Tipo de arquivo inválido para <strong>foto de perfil</strong>."];
        }

        return ["success" => true, "message" => ""];
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
                $isUnique = UserEntity::isUnique($key, $value);
                
                if (!$isUnique["success"]) {
                    return [
                        "success" => false,
                        "message" => "Erro na conexão. Tente novamente." ,
                    ];
                }

                if (!$isUnique["value"]) {
                    return ["success" => false, "message" => "Valor de <strong>".$settings["label"]."</strong> já existe."];
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