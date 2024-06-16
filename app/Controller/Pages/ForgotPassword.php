<?php
namespace App\Controller\Pages;

require_once(__DIR__ . "/../../Model/Entity/User.php");
require_once(__DIR__."/../../Utils/View.php");
require_once(__DIR__."/../../Session/Login.php");
require_once(__DIR__."/Page.php");

use \App\Utils\View;
use \App\Model\Entity\User;
use \App\Session\Login as SessionLogin;



class ForgotPassword {

    public static function render() {
        return View::render("pages/forgot-password/index", [
        ]);
    }

}