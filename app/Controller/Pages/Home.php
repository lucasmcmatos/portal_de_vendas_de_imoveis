<?php
namespace App\Controller\Pages;

require_once(__DIR__ . "/../../Utils/View.php");
require_once(__DIR__."/../../Session/Login.php");
require_once(__DIR__ . "/Page.php");

use \App\Utils\View;
use \App\Session\Login;

class Home extends Page {

    public static function render() {

        $homeButtonRoute = Login::isLogged() ? "platform-advertising" : "register";
        $homeButtonTitle = Login::isLogged() ? "Buscar Anúncios da Plataforma" : "Cadastre-se Agora";

        $content = View::render("pages/home/index", [
            "homeButtonRoute" => $homeButtonRoute,
            "homeButtonTitle" => $homeButtonTitle,
        ]);
        return parent::renderPage("Início", $content, "style=\"overflow: hidden;\"");
    }
}