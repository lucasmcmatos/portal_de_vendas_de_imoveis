<?php

/*
namespace App\Controller\Pages;

require_once(__DIR__."/../../Model/Entity/User.php");
require_once(__DIR__."/../../Utils/View.php");
require_once(__DIR__."/../../Core/UserValidation.php");
require_once(__DIR__."/Page.php");

use \App\Utils\View;
use \App\Model\Entity\User as UserEntity;
use \App\Core\UserValidation;

class Settings extends Page {
    public static function render() {

        $result = UserEntity::getUserById($_SESSION["user"]["id"]);

        if (!$result["success"]) {
            return "";
        }

        $objUser = $result["value"];

        $cpfMask = "###.###.###-##";
        $cpfMaskArray = str_split($cpfMask);
        $cpfArray = str_split($objUser->cpf);
        
        foreach($cpfMaskArray as $index => $cpfChar){
            if ($cpfChar === "#") {
                $cpfMask[$index] = array_shift($cpfArray);  
            }
        }
        

        $content = View::render("pages/settings", [
            "id" => $objUser->id,
            "username" => $objUser->username,
            "fullname" => $objUser->fullname, 
            "email" => $objUser->email, 
            "cpf" => $cpfMask, 
            "profile" => $objUser->photo, 
        ]);

        return parent::renderPage("Configurações", $content);
    }

}*/