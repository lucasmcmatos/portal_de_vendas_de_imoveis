<?php
namespace App\Controller\Pages;

require_once(__DIR__."/../../Utils/View.php");
require_once(__DIR__."/../Api/News.php");
require_once(__DIR__."/NotFound.php");

use \App\Utils\View;
use \App\Controller\Api\News as NewsApi;

class Preview {
    public static function render($request, $id = 0) {
        if (intval($id)) {
            $objNews = NewsApi::getById($id);
            return View::render("pages/preview", $objNews);
        }

        return NotFound::render();
    }
}