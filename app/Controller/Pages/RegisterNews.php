<?php
namespace App\Controller\Pages;

require_once(__DIR__."/../../Model/Entity/User.php");
require_once(__DIR__."/../../Utils/View.php");
require_once(__DIR__."/../../Core/UserValidation.php");
require_once(__DIR__."/Page.php");

use \App\Utils\View;
use \App\Model\Entity\News as NewsEntity;

class RegisterNews extends Page {



    private static function render($request, $path, $id = "") {

        $result = NewsEntity::getById($id);

        $objNews = new NewsEntity();

        if ($result["success"]) {
            $objNews = $result["value"];
        }


        $pageTitle = $id == "" ? "Cadastrar notícia" : "Editar notícia";
        $processTitle =  $id == "" ? "Cadastrando" : "Editando";
        $errorTitle =  $id == "" ? "cadastrar" : "editar";

        $content = View::render("pages/register-news", [
            "id" => $objNews->id ?? "",
            "title" => $objNews->title ?? "",
            "body" => $objNews->body ?? "",
            "summary" => $objNews->summary ?? "",
            "photo" => $objNews->photo ?? "news-default.png",
            "featured" => $objNews->featured ? "checked":"",
            "schedule" => $objNews->schedule ?? "",
            "path" => $path ?? "",
            "pageTitle" => $pageTitle,
            "processTitle" => $processTitle,
            "errorTitle" => $errorTitle
        ]);

        return parent::renderPage($pageTitle, $content);
    }





    public static function renderRegister($request) {
       return self::render($request, "api/news"); 
    }





    public static function renderEdit($request, $id) {
       return self::render($request, "api/news/edit", $id); 
    }
}