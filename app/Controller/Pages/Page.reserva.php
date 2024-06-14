<?php
namespace App\Controller\Pages;

require_once(__DIR__."/../../Utils/View.php");
require_once(__DIR__."/../../Session/Login.php");
require_once(__DIR__."/../../Model/Entity/User.php");

use \App\Utils\View;
use \App\Session\Login;
use \App\Model\Entity\User as UserEntity;

class Page {

    private static function getSideBar() {
        $userItems = "";
        $modeItems = "";
        $adminItems = "";

        return View::render("components/sidebar", [
            "userItems" => $userItems,
            "modeItems" => $modeItems,
            "adminItems" => $adminItems
        ]);
    }
    

    private static function getTopBar() {
        $userInfo = Login::getLoggedUserInfo();        
        $result = UserEntity::getUserById($userInfo["id"]);

        if (!$result["success"]) {
            return "";
        }

        $objUser = $result["value"];

        return View::render("components/topbar", [
            "username" => $objUser->username, 
            "photo" => $objUser->photo, 
        ]);
    }

    public static function renderPage($title, $content) {
        $header = View::render("pages/header");
        return View::render("pages/page", [
            "title" => $title,
            "content" => $content,
            "sidebar" => self::getSideBar(),
            "topbar" => self::getTopBar(),
        ]);
    }
}