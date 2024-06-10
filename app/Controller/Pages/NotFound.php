<?php
namespace App\Controller\Pages;

require_once(__DIR__."/../../Utils/View.php");
require_once(__DIR__."/Page.php");

use \App\Utils\View;

class NotFound {
    public static function render() {
        return View::render("pages/404");
    }
}