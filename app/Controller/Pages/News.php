<?php
namespace App\Controller\Pages;

require_once(__DIR__."/../../Model/Entity/News.php");
require_once(__DIR__."/../../Utils/View.php");
require_once(__DIR__."/Page.php");

use \App\Utils\View;
use \App\Model\Entity\News as NewsEntity;
use Exception;

class News extends Page {

    public static function getRegisterForm() {
        return View::render("pages/news/register-form");
    }


    public static function getEditForm() {
        return View::render("pages/news/edit-form");
    }


    public static function render() {
        $content = View::render("pages/news/index", [ 
            "registerForm" => self::getRegisterForm(),
            "editForm" => self::getEditForm()
        ]);

        return parent::renderPage("Notícias", $content);  
    }



}

