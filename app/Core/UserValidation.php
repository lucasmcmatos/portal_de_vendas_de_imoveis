<?php
namespace App\Core;

require_once(__DIR__."/../Model/Entity/User.php");

use \App\Model\Entity\User as UserEntity;

class UserValidation {

    static $validateMap = [
        "fullname" => [
            "label" => "nome completo",
            "validate" => "\App\Core\UserValidation::fullname",
            "required" => true,
            "unique" => false,
        ],
        "username" => [
            "label" => "nome de usuário",
            "validate" => "\App\Core\UserValidation::username",
            "required" => true,
            "unique" => true,
        ],
        "email" => [
            "label" => "email",
            "validate" => "\App\Core\UserValidation::email",
            "required" => true,
            "unique" => true,
        ],
        "cpf" => [
            "label" => "CPF",
            "validate" => "\App\Core\UserValidation::cpf",
            "required" => true,
            "unique" => true,
        ],
        "new-password" => [
            "label" => "nova senha",
            "validate" => "\App\Core\UserValidation::password",
            "required" => true,
            "unique" => false,
        ],
        "photo" => [
            "label" => "foto",
            "validate" => "\App\Core\UserValidation::photo",
            "required" => false,
            "unique" => false,
        ]
    ];


    private static function fullname($fullname) {
        $alphaRegex = "/[^\sa-zA-ZàáâãéêíóôõúÀÁÂÃÉÊÍÓÔÕÚçÇ']|\s{2,}|'{2,}|^'/";
        if (preg_match($alphaRegex, $fullname) || strlen($fullname) < 3 || strlen($fullname) > 255) {
            return ["success" => false, "message" => "<strong>Nome completo<strong> inválido."];
        }

        return ["success" => true, "message" => ""];
    }



    private static function username($username) {
        //$alphaRegex = "/^[a-zA-Z0-9_.]{3,255}$/";
        $usernameRegex = "/^(?=.{5,20}$)(?![_.])(?!.*[_.]{2})[a-zA-Z0-9._]+(?<![_.])$/";

        if (!preg_match($usernameRegex, $username)) {
            return ["success" => false, "message" => "<strong>Nome de usuário</strong> inválido."];
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





    private static function cpf($cpf) {
        if (!self::validateCpf($cpf)) {
            return ["success" => false, "message" => "<strong>CPF</strong> inválido"];
        }

        return ["success" => true, "message" => ""];
    }





    private static function password($password) {
        $passRegex = "/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&\.])[A-Za-z\d@$!%*?&\.]{8,}$/";

        if (!preg_match($passRegex, $password)) {
            return ["success" => false, "message" => "<strong>Senha</strong> não é forte o suficiente."];
        }

        return ["success" => true, "message" => ""];
    }




    private static function photo($tmpfile) {
        $validTypes = ["image/jpeg", "image/png"];

        if (!file_exists($tmpfile)) {
            return ["success" => false, "message" => "Problema ao salvar </strong>foto de perfil<strong>."];
        }

        if (filesize($tmpfile) > 5000000) {
            return ["success" => false, "message" => "Tamanho máximo de 5MB excedido para <strong>foto de perfil</strong>."];
        }

        if (!in_array(mime_content_type($tmpfile), $validTypes)) {
            return ["success" => false, "message" => "Tipo de arquivo inválido para <strong>foto de perfil</strong>."];
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