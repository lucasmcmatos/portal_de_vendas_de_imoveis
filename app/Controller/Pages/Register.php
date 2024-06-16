<?php

namespace App\Controller\Pages;

require_once(__DIR__."/../../Model/Entity/User.php");
require_once(__DIR__."/../../Utils/View.php");
require_once(__DIR__."/../../Core/UserValidation.php");
require_once(__DIR__."/Page.php");


use \App\Utils\View;


class Register {

    public static function render() {
        return View::render("pages/register/index", [
        ]);
    }

}