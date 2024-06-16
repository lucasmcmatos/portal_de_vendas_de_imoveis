<?php
namespace App\Controller\Pages;

require_once(__DIR__ . "/../../Utils/View.php");
require_once(__DIR__."/../../Session/Login.php");
require_once(__DIR__ . "/Page.php");

use \App\Utils\View;
use \App\Session\Login;

class PlatformAdvertising extends Page {

    public static function render() {

        $content = View::render("pages/platform-advertising/index", [
        ]);
        return parent::renderPage("Anúncios da Plataforma", $content, "style=\"overflow: hidden;\"");
    }
}