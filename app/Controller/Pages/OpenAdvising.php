<?php
namespace App\Controller\Pages;

require_once(__DIR__ . "/../../Utils/View.php");
require_once(__DIR__ . "/Page.php");

use \App\Utils\View;

class Home extends Page {
    
    public static function render() {
        $content = View::render("pages/open-advising/index");
        return parent::renderPage("Anúncio", $content);
    }
}