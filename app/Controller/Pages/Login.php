<?php
namespace App\Controller\Pages;

require_once(__DIR__."/../../Utils/View.php");
require_once(__DIR__."/../../Session/Login.php");
require_once(__DIR__."/Page.php");

use \App\Utils\View;
use \App\Model\Entity\User;
use \App\Session\Login as SessionLogin;



class Login {

    public static function render($errorMessage = null) {
        return View::render("pages/login/index", [
        ]);
    }



    public static function setLogin($request) {
        $postVars = $request->getPostVars();
        
        $identifier = $postVars["identifier"] ?? "";
        $password = $postVars["password"] ?? "";

        $result = User::getUserByIdentifier($identifier);

        if (!$result["success"]) {
            return json_encode(["success" => false, "message" => "Falha ao efetuar login"]);
        }
  
        $objUser = $result["value"];

        if (!($objUser instanceof User)) {
            return json_encode(["success" => false, "message" => "Email e/ou senha inválidos"]);
        }

        if(!password_verify($password, $objUser->password)) {
            return json_encode(["success" => false, "message" => "Email e/ou senha inválidos"]);
        }

        SessionLogin::login($objUser);

        return json_encode(["success" => true, "message" => ""]);
    }

    

    public static function setLogout($request) {
        SessionLogin::logout();
        $request->getRouter()->redirect("/");
    }
}