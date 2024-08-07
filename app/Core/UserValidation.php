<?php
namespace App\Core;

require_once(__DIR__."/../Model/Entity/User.php");

use \App\Model\Entity\User as UserEntity;
use DateTime;

class UserValidation {

    private static string $password;

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

        "confirmPassword" => [
            "label" => "confirmação de senha",
            "selector" => "confirmPassword",
            "validate" => "\App\Core\UserValidation::confirmPassword",
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
        $birthDateRegex = "/^[0-9]{4}-[0-9]{2}-[0-9]{2}$/";

        if (!preg_match($birthDateRegex, $birthDate)) {
            return ["success" => false, "message" => "<strong>Data de nascimento</strong> inválida!"];
        }


        $now = new DateTime();
        $birthDate = new DateTime($birthDate);

        if($birthDate->diff($now)->y <= 12){
            return ["success" => false, "message" => "Você deve ter pelo menos 13 anos para realizar cadastro na plataforma."];
        }


        return ["success" => true, "message" => ""];
    }



    private static function password($password) {

        $passwordRegex = "/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&\.])[A-Za-z\d@$!%*?&\.]{8,}$/";

        if (!preg_match($passwordRegex, $password)) {
            return ["success" => false, "message" => "A <strong>senha</strong> não é forte o suficiente. 
            Necessário ter pelo menos 8 caracteres, 1 letra minúscula, 1 letra maiúscula, 1 algarismo e um caractere especial (@$!%*?&.)"];
        }

        self::$password = $password;

        return ["success" => true, "message" => ""];
    }


    private static function confirmPassword($confirmPassword) {

        if (self::$password != $confirmPassword) {
            return ["success" => false, "message" => "As senhas não coincidem. Por favor, verifique e tente novamente."];
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

        // $phoneRegex = "/^\([0-9]{2}\)\s[0-9]{4,5}-[0-9]{4}$/";
        // $phoneRegexMatch = preg_match($phoneRegex, $phone);

        // $phone = preg_replace("/[^0-9]/", "", $phone);

        // if ($phoneRegexMatch && strlen($phone) <= 11) {
        //     return [
        //         "success" => true, 
        //         "message" => "Telefone válido"
        //     ];
        // }

        return [
            "success" => true, 
            "message" => "Telefone válido"
        ];



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

        // $whatsappRegex = "/^\([0-9]{2}\)\s[0-9]{5}-[0-9]{4}$/";
        // $whatsappRegexMatch = preg_match($whatsappRegex, $whastapp);

        // $phone = preg_replace("/[^0-9]/", "", $whastapp);

        // if ($whatsappRegexMatch && strlen($phone) == 11) {
        //     return [
        //         "success" => true, 
        //         "message" => "Whatsapp válido"
        //     ];
        // }

        return ["success" => true, "message" => "Whatsapp válido"];
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