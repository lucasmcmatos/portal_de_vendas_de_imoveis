<?php
namespace App\Core;

require_once(__DIR__."/../Model/Entity/News.php");

use \DateTime;
use \App\Model\Entity\News as NewsEntity;


class NewsValidation {

    static $validateMap = [
        "title" => [
            "label" => "título da notícia",
            "validate" => "\App\Core\NewsValidation::title",
            "required" => true,
            "unique" => false,
        ],
        "schedule" => [
            "label" => "agendamento da notícia",
            "validate" => "\App\Core\NewsValidation::schedule",
            "required" => false,
            "unique" => false,
        ],
        "summary" => [
            "label" => "resumo da notícia",
            "validate" => "\App\Core\NewsValidation::summary",
            "required" => true,
            "unique" => false,
        ],
        "body" => [
            "label" => "corpo da notícia",
            "validate" => "\App\Core\NewsValidation::body",
            "required" => true,
            "unique" => false,
        ],
        "featured" => [
            "label" => "gerenciar destaque da notícia",
            "validate" => "\App\Core\NewsValidation::featured",
            "required" => true,
            "unique" => false,
        ],

        "photo" => [
            "label" => "imagem da notícia",
            "validate" => "\App\Core\NewsValidation::photo",
            "required" => false,
            "unique" => false,
        ],

        "author" => [
            "label" => "autor da notícia",
            "validate" => "\App\Core\NewsValidation::author",
            "required" => true,
            "unique" => false,
        ], 
        "visible" => [
            "label" => "gerenciar visibilidade da notícia",
            "validate" => "\App\Core\NewsValidation::visible",
            "required" => true,
            "unique" => false,
        ],
    ];



    private static function photo($tmpName) {
        $validTypes = ["image/jpeg", "image/png", "image/webp", "image/svg+xml"];

        if (!file_exists($tmpName)) {
            return ["success" => false, "message" => "Problema ao salvar <strong>imagem da notícia</strong>."];
        }

        if (filesize($tmpName) > 5000000) {
            return ["success" => false, "message" => "Tamanho máximo de 5MB excedido para <strong>imagem da notícia</strong>."];
        }

        if (!in_array(mime_content_type($tmpName), $validTypes)) {
            return ["success" => false, "message" => "Tipo de arquivo inválido para <strong>imagem da notícia</strong>."];
        }

        return ["success" => true, "message" => ""];
    }





    private static function title($title) {
         $alphaRegex = "/[^\sa-zA-Z0-9àáâãéêíóôõúÀÁÂÃÉÊÍÓÔÕÚçÇ']/";
         if (preg_match($alphaRegex, $title)){
            return ["success" => false, "message" => "<strong>Título da notícia</strong> contém caracteres inválidos."];
         } 


        if(strlen($title) < 10 || strlen($title) > 100)  {
            return ["success" => false, "message" => "<strong>Título da notícia</strong> deve ter entre 10 e 100 catacteres."];
        }

        return ["success" => true, "message" => ""];
    }





    private static function summary($summary) {
         $alphaRegex = "/[^\sa-zA-Z0-9àáâãéêíóôõúÀÁÂÃÉÊÍÓÔÕÚçÇ']/";

         if (preg_match($alphaRegex, $summary)) {
            return ["success" => false, "message" => "<strong>Resumo da notícia</strong> contém caracteres inválidos."];
         } 
         
         
         if (strlen($summary) < 100 || strlen($summary) > 150) {
             return ["success" => false, "message" => "<strong>Resumo da notícia</strong> deve ter entre 100 e 150 caracteres."];
         }
        return ["success" => true, "message" => ""];
    }




    private static function body($body) {
        //implement validation;
        return ["success" => true, "message" => ""];
    }



    
    private static function schedule($schedule) {


        //update validation
        $regex = "/^\d{4}-\d{2}-\d{2}\s\d{2}:\d{2}:00$/";

        if (!preg_match($regex, $schedule) || (new DateTime($schedule))->format("Y-m-d H:i:s") != $schedule) {
            return ["success" => false, "message" => "<strong>Data de agendamento</strong> inválida."];
        }

        $now = new DateTime();
        $schedule = new DateTime($schedule);


        if($schedule->format("Y-m-d H:i") !== $now->format("Y-m-d H:i") && $schedule < $now){
            return [
                "success" => false, 
                "message" => "A <strong>data de agendamento</strong> não pode ser anterior à data atual."
            ];
        }


        return ["success" => true, "message" => ""];
    }



    public static function featured($featured) {
        
        if (!in_array($featured, ["true", "false"])) {
            return ["success" => false, "message" => ""];
        }

        return ["success" => true, "message" => ""];
    }




    public static function author($author) {
        $authorRegex = "/^(?=.{5,20}$)(?![_.])(?!.*[_.]{2})[a-zA-Z0-9._]+(?<![_.])$/";

        if (!preg_match($authorRegex, $author)) {
            return ["success" => false, "message" => "<strong>Autor de notícia</strong> inválido."];
        }

        return ["success" => true, "message" => ""];
    }


    public static function visible($visible) {
        
        if (!in_array($visible, ["true", "false"])) {
            return ["success" => false, "message" => ""];
        }

        return ["success" => true, "message" => ""];
    }




    

    public static function validateForm($form) {

        foreach ($form as $key => $value) {

            $settings = self::$validateMap[$key];

            if (empty($value)) {
                if ($settings["required"]) {
                    return ["success" => false, "message" => "O campo <strong>".$settings["label"]."</strong> é obrigatório." ];
                }

                continue;
            }


            if ($settings["unique"]) {
                $isUnique = NewsEntity::isUnique($key, $value);
                
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