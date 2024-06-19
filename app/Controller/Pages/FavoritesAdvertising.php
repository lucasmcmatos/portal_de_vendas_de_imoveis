<?php
namespace App\Controller\Pages;

require_once(__DIR__ . "/../../Utils/View.php");
require_once(__DIR__."/../../Session/Login.php");
require_once(__DIR__ . "/Page.php");

use \App\Utils\View;
use \App\Session\Login;

class FavoritesAdvertising extends Page {

    public static function render() {

        $content = View::render("pages/favorites-advertising/index", [
        ]);
        return parent::renderPage("Meus favoritos", $content);
    }
}